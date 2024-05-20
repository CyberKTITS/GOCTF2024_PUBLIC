FROM python:3.8-slim

RUN pip install telebot

COPY ./telegram.py /app/telegram.py
WORKDIR /app

CMD ["python", "telegram.py"]
