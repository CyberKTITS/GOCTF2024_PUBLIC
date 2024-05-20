from flask import Flask, request, render_template, render_template_string, jsonify, redirect, make_response, url_for
from sqlalchemy import and_
from models.models import User, Post, db
from bcrypt import hashpw, checkpw, gensalt
from jwt import encode, decode
import time
import datetime

app = Flask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+pymysql://root:422a04445515881b120784a7@mysql:3306/files?charset=utf8'

app.config['SECRET_KEY'] = '14ffd046f3ecb709963dca8114f848ca47e121b75a83b88b478d970f1b2f7330'
app.config['JSON_AS_ASCII'] = False
app.config['MYSQL_CHARSET'] = 'utf8mb4'


db.init_app(app)

@app.route('/')
def index():
    if not request.cookies.get('Access-Token'):
        return redirect('/login')
    
    return redirect(url_for('get_all_posts'))


@app.route('/login', methods = [ 'GET', 'POST' ])
def login():
    if request.method == 'GET':
        if get_user_from_cookie():
            return redirect('/', 301);

        return render_template('login.html')
    else:
        return authorize(request.form['login'], request.form['password'])


@app.route('/logout', methods = [ 'POST' ])
def logout():
    if not request.cookies.get('Access-Token'):
        return 400
    
    response = make_response(redirect(url_for('login')))
    response.delete_cookie('Access-Token')

    return response, 200


def authorize(login: str, password: str):
    users = User.query.filter(User.login == login)
    if users.count() == 0:
        return jsonify(
            {
                'highlight_elements': [
                    'login', 'password'
                ],
                'alert': [
                    {
                        'selector': 'form',
                        'message': 'Неверный логин или пароль'
                    }
                ]
            }), 400
    
    authorized_user: User = None
    for user in users:
        if checkpw(password.encode(), user.password.encode()):
            authorized_user = user;
    
    if (not authorized_user):
        return jsonify(
            {
                'highlight_elements': [
                    'login', 'password'
                ],
                'alert': [
                    {
                        'selector': 'form',
                        'message': 'Неверный логин или пароль'
                    }
                ]
            }), 400

    response = make_response(redirect("/", 200))
    response.set_cookie('Access-Token', encode({ "user_id": authorized_user.id }, app.config['SECRET_KEY'], algorithm='HS256'), max_age=60 * 60 * 24)

    return response


@app.route('/register', methods = [ 'GET', 'POST' ])
def register():
    if request.method == 'GET':
        if get_user_from_cookie():
            return redirect('/')

        return render_template('register.html')
    else:
        username, login, password, password_repeat = request.form.values()

        error = validate_register_form(username, login, password, password_repeat)
        if error:
            return jsonify(error), 400

        return create_user(username, login, password)


def validate_register_form(username, login, password, password_repeat):
    if not username.strip():
        return {
            'highlight_elements': [
                'username'
            ],
            'alert': [
                {
                    'selector': '.input_container:has(input[name="username"])',
                    'message': 'Имя пользователя не заполнено'
                }
            ]
        }

    if User.query.filter(User.username == username).first():
        return {
            'highlight_elemets': [
                'username'
            ],
            'alert': [
                {
                    'selector': '.input_container:has(input[name="username"])',
                    'message': 'Имя пользователя уже занято'
                }
            ]
        }

    if password != password_repeat:
        return {
            'highlight_elements': [
                'password', 'password_repeat'
            ],
            'alert': [
                {
                    'selector': '.input_container:has(input[name="password_repeat"])',
                    'message': 'Пароли отличаются'
                }
            ]
        }

    return None


def create_user(username, login, password):    
    new_user = User(username=username, login=login, password=hashpw(password.encode(), gensalt()))

    db.session.add(new_user)
    db.session.commit()
    return redirect(url_for("login"), 200)


@app.route('/posts', methods = [ 'GET' ])
def get_all_posts():
    user: User = get_user_from_cookie()
    if not user:
        return redirect(url_for('login'))

    #return render_template('posts.html', user=user, posts=Post.query.filter((Post.private == False) | (Post.author_id == user.id)), title='Все записи')

    return render_posts(user=user, posts=Post.query.filter((Post.private == False) | (Post.author_id == user.id)), title='Все записи')


def render_posts(user, posts, title):
    list_template = """
{% extends "header_base.html" %}
{% block title %}{{ title }}{% endblock %}
{% block head %}
{{ super() }}

<link rel="stylesheet" href="{{ url_for('static', filename='styles/posts.css') }}">

{% endblock %}
{% block content %}
{{ super() }}

<div class="post_container">
    {% for post in posts %}
        {{ render_template(post, posts)|safe }}
    {% endfor %}
</div>
{% endblock %}
"""
    
    return render_template_string(list_template, user=user, posts=posts, title=title, render_template=render_post)

def render_post(post, posts):
    template = f"""
<div class="post">
        <a href="{{{{ url_for('get_post', post_id=post.id) }}}}">
            <div class="post_header">
                <div class="row">
                    <span class="fa fa-solid fa-user"></span>
                    <div class="column">
                        <div class="author">{{{{ post.author.username }}}}</div>
                        <div class="created_at">{{{{ post.created_at | datetime_format }}}}</div>
                    </div>
                </div>
                <div class="title">{ post.title }</div>
            </div>
        </a>
    </div>
"""
    try:
        return render_template_string(template, post=post, posts=posts)
    except:
        return ""


@app.route('/posts/my', methods = [ 'GET' ])
def get_my_posts():
    user: User = get_user_from_cookie()
    if not user:
        return redirect(url_for('login'))

    #return render_template('posts.html', user=user, posts=Post.query.filter(Post.author_id == user.id), title='Мои записи')
    return render_posts(user=user, posts=Post.query.filter(Post.author_id == user.id), title='Мои записи')


@app.route('/posts/<int:post_id>', methods = [ 'GET' ])
def get_post(post_id):
    user: User = get_user_from_cookie()
    if not user:
        return redirect(url_for('login'), 200)
    

    post: Post = Post.query.get(post_id)
    if not post:
        return render_template('notFound.html', user=user), 404
    
    if post.private == 1 and post.author_id != user.id:
        return render_template('accessDenied.html', user=user), 403

    return render_template('post.html', post=post, user=user), 200


@app.route('/posts/create', methods = [ 'GET', 'POST' ])
def create_post():
    user: User = get_user_from_cookie()
    if not user:
        return redirect('/login'), 200

    if request.method == 'GET':
        return render_template('create_post.html', user=user)
    else:
        title = request.form['title']
        text = request.form['text']

        if '__' in title or 'builtins' in title or 'globals' in title:  # TODO: требует дальнейшей доработки от RCE
            return jsonify(
            {
                'highlight_elements': [
                    'title'
                ],
                'alert': [
                    {
                        'selector': '.input_container:has(input[name="title"])',
                        'message': 'Недопустимые символы'
                    }
                ]
            }), 400

        user: User = get_user_from_cookie()
        if not user:
            return redirect(url_for('login'))
        
        db.session.add(Post(title=title,
                            description=text,
                            created_at=datetime.datetime.now(), 
                            author=user, 
                            private=True))
        db.session.commit()

        return redirect(url_for('get_my_posts'), 200)


@app.template_filter('datetime_format')
def datetime_format(value):
    return value.strftime('%d.%m.%Y %H:%M:%S')


@app.template_filter('title_format')
def title_format(value: str):
    return value[0:min(len(value), 250)]


def get_user_from_cookie():
    if not request.cookies.get('Access-Token'):
        return None
    try:
        payload = decode(request.cookies["Access-Token"], app.config['SECRET_KEY'], algorithms=["HS256"])
        return User.query.get(payload["user_id"])
    except:
        return None


if __name__ == '__main__':
    app.run(debug=False, host='0.0.0.0', port=8080);
