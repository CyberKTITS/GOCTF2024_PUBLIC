FROM python:3.8-slim

RUN pip install Flask

COPY . /app
WORKDIR /app

EXPOSE 5005

CMD ["python", "app.py"]
