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

CREATE TABLE cars (
	id varchar(16) primary key,
	registration VARCHAR(9) NOT NULL,
	`name` VARCHAR(50) NOT NULL,
	display BOOL DEFAULT true
);

CREATE TABLE trips (
	id INT AUTO_INCREMENT PRIMARY KEY,
	overwriten_by INT NULL,

	car varchar(16) NOT NULL,
	`driver` VARCHAR(16) NOT NULL,
	
	added_by VARCHAR(16) NULL,
	added_on DATETIME NULL,
	removed_by VARCHAR(16) NULL,
	removed_on DATETIME NULL,
	
	start_odometer INT NOT NULL,
	start_place VARCHAR(50) NOT NULL,
	start_date DATETIME NOT NULL,
	
	target_client VARCHAR(50) NULL,
	target_place VARCHAR(50) NULL,
	
	end_odometer INT NULL,
	end_place VARCHAR(50) NULL,
	end_date DATETIME NULL,

	is_personal BOOL NULL,
	and_back BOOL NULL,

	FOREIGN KEY (`overwriten_by`) REFERENCES trips(id),
	FOREIGN KEY (`car`) REFERENCES cars(id),
	FOREIGN KEY (`driver`) REFERENCES users(id),
	
	FOREIGN KEY (`added_by`) REFERENCES users(id),
	FOREIGN KEY (`removed_by`) REFERENCES users(id)
);

ALTER TABLE trips ADD (
	note TEXT null
);

