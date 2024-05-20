create database if not exists post;

use post;

create table if not exists user(
    id int not null primary key auto_increment,
    username nvarchar(250) not null,
    login nvarchar(250) not null,
    password nvarchar(250) not null
);

create table if not exists post(
    id int not null primary key auto_increment,
    user_id int not null,
    title nvarchar(250) not null,
    description text not null,
    access_token nvarchar(200) not null unique,
    created_at int not null,

    foreign key(user_id) references user(id)
);

insert into user(id, username, login, password) values(-1912, 'Неизвестный', '', '');
insert into post(user_id, title, description, access_token, created_at) values(-1912, 'Этот пост содержит флаг', 'Флаг: GOCTF{2cf998c78_e632f3d3f4a_273540}', '4808ce00238fe084a3035e93e64e2d8c', 1706805161);