create database if not exists users;

use users;

create table if not exists user (
    id int not null primary key auto_increment,
    username nvarchar(250) not null,
    login nvarchar(300) not null,
    password nvarchar(300) not null,
    role nvarchar(200) not null,

    check(role in ('admin', 'guest'))
);

insert into user(username, login, password, role) values('Dima', '84983c60f7daadc1cb8698621f802c0d9f9a3c3c295c810748fb048115c186ec1', '84983c60f7daadc1cb8698621f802c0d9f9a3c3c295c810748fb048115c186ec', 'guest');