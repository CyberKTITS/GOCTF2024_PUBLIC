FROM python:latest

WORKDIR /app

COPY requirements.txt /tmp/requirements.txt

RUN [ "pip", "install", "-r", "/tmp/requirements.txt" ]

COPY /source /app

ENTRYPOINT [ "python", "main.py" ]