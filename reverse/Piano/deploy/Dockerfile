FROM python:3.8-slim

RUN pip install Flask

WORKDIR /app
COPY ./app.py /app/app.py

EXPOSE 5001

CMD ["python", "app.py"]
