create table users (
	id varchar(16) primary key,
	fullname varchar(80) not null
);

create table credentials (
	id varchar(16) primary key,
	`user` varchar(16), 
	name varchar(50) not null,
	pass varchar(255) not null,
	provided_groups varchar(255) not null,
	foreign key (`user`) references users(id)
);


