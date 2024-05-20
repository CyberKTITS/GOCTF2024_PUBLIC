FROM ubuntu:22.04

RUN apt-get -y update
RUN apt-get -y install socat gcc

RUN useradd -m ctf
RUN mkdir /app
WORKDIR /app

COPY main.c /app
COPY flag /app

RUN gcc main.c -o Link -no-pie
RUN rm main.c

USER ctf

EXPOSE 17171
CMD socat tcp-listen:17171,reuseaddr,fork exec:"./Link"
