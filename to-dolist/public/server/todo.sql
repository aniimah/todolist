create database Todolist;
use Todolist;

create table User(
userId int not null key auto_increment,
Username varchar(25) not null unique,
Fullname varchar(100) not null,
email varchar(50) not null,
date_commenced date not null,
password varchar(100) NOT NULL
);

create table Todo(
todoId int primary key auto_increment,
title varchar(100),
task text not null, 
category varchar(50),
completed bool,
date_created date not null,
due_date date not null,
userId int,
foreign key (userId) references User(userId)
);