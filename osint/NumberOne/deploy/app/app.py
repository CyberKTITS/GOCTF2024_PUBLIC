from flask import Flask, request

app = Flask(__name__)

@app.route('/')
def hello_world():
    return """<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>task osint</title>
    <style>
        html, body {
            height: 100%;
        }

        body {
            background-repeat: repeat;
            background: linear-gradient(to bottom, #000000a0, #000000a0), url('static/world.png') no-repeat 50%, linear-gradient(to bottom, #87CEEB, #00bfff);
            color: white; 
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
        background: #69696990;
        border: 1px solid black;
        border-radius: 15px;
        color: white;
        padding: 5px; 
        }

        form {
            margin-top: 10px;
        }

        input[type="submit"] {
    border-radius: 5px;
    background-color: blue;
    color: white; 
    padding: 8px 16px;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s;
}
      input[type="submit"]:hover {
      background-color: black;
}
        }

        label {
            display: inline-block;
            font-size: 18px;
            text-align: left;
            margin-right: 10px;
            margin-bottom: 5px;
        }

        #y {
            margin-top: 5px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Введите координаты и получите флаг!</h1>
        <form onsubmit="submitForm(); return false;">
            <label for="x">Широта:</label>
            <input name="x" id="x"/>
            <br>
            <label for="y">Долгота:</label>
            <input name="y" id="y"/>
            <p></p>
            <input type="submit" value="Отправить"/>
        </form>
        <p id="flag"></p>
    </div>
    <script>
        function submitForm() {
            var x = document.getElementById("x").value;
            var y = document.getElementById("y").value;

            fetch("/try", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "x=" + x + "&y=" + y
            })
            .then(response => response.text())
            .then(data => document.getElementById("flag").textContent = data);
        }
    </script>
</body>
</html>
"""

@app.route('/try', methods=["POST"])
def try_flag():
    try:
        x = request.form['x']
        y = request.form['y']

        if x == "31.8996414" and y == "-102.2391903":
            return """Flag is GOCTF{it11_d0_f0r_@_5tar7}."""
    except Exception as e:
        print(e)

    return """Nice try!"""

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5005)
