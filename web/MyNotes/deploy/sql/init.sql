create database files;

use files;

create table user(
    id int primary key auto_increment not null,
    username nvarchar(250) not null,
    login nvarchar(250) not null,
    password nvarchar(250) not null,

    unique key(username)
);

create table post(
    id int primary key not null auto_increment,
    title text not null,
    description text not null,
    created_at datetime not null,
    private bool not null default(true),

    author_id int not null,

    foreign key(author_id) references user(id)
);

insert into user(id, username, login, password)
values(-412, "Хранитель флага", '159e22ec4edaa8a694913d5c08c0b6671130587da9e1acf845343ffaf5ad101e', '');

alter table user auto_increment = 0;

insert into post(title, description, created_at, private, author_id)
values ("Эта запись содержит флаг", 'Тут ничего нет... Может я сделал запись приватной...', '2024-04-18 12:23:11', false, -412),
       ("Тут 100% есть флаг", 'GOCTF{085f2e57ef053aa01af99bad04085b7}', '2024-04-20 12:23:11', true, -412);
