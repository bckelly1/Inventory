create database Inventory;
create table book (
	class char(1) DEFAULT 'H', item_id int PRIMARY KEY AUTO_INCREMENT, title VARCHAR(30) NOT NULL, author VARCHAR(30),
    ISBN VARCHAR(30), genre int, format int, cost double, FOREIGN KEY (class) REFERENCES classes(class)
);

Create table clothing (
	class char(1) DEFAULT 'B', item_id int PRIMARY KEY AUTO_INCREMENT, title VARCHAR(30) NOT NULL,
    description VARCHAR(60), color int, formality int, cost double, FOREIGN KEY (class) REFERENCES classes(class)
);

create table computer (
	class char(1) DEFAULT 'P', item_id int PRIMARY KEY AUTO_INCREMENT, title VARCHAR(30) NOT NULL, type VARCHAR(30), processor VARCHAR(30),
    memory VARCHAR(10), disk_space VARCHAR(30), bought date, cost double, IP_ADDRESS VARCHAR(30), FOREIGN KEY (class) REFERENCES classes(class)
);

Create table first_aid (
	class char(1) DEFAULT 'C', item_id int PRIMARY KEY AUTO_INCREMENT, title varchar(30) NOT NULL,
    expiration date, child_friendly boolean, location VARCHAR(30), type_of_injury int, cost double,
    FOREIGN KEY (class) REFERENCES classes(class)
);

Create table food (
	class char(1) DEFAULT 'A', title VARCHAR(30) NOT NULL, item_id int PRIMARY KEY AUTO_INCREMENT, type VARCHAR(30),
    tag int, manny_friendly boolean, common_list boolean, cost double, FOREIGN KEY (class) REFERENCES classes(class)
);

create table food_processor (
	class char(1) DEFAULT 'M', item_id int PRIMARY KEY AUTO_INCREMENT, title VARCHAR(30) NOT NULL,  capacity real,
    capacity_type int, cost double, FOREIGN KEY (class) REFERENCES classes(class)
);

create table furniture (
	class char(1) DEFAULT 'N', item_id int PRIMARY KEY AUTO_INCREMENT, title VARCHAR(30) NOT NULL,
    specs VARCHAR(30), dimensions VARCHAR (30), bought date, cost double, FOREIGN KEY (class) REFERENCES classes(class)
);

create table garden_tool (
	class char(1) DEFAULT 'O', item_id int PRIMARY KEY AUTO_INCREMENT, title VARCHAR(30) NOT NULL, sterilizable boolean,
    cost double, FOREIGN KEY (class) REFERENCES classes(class)
);

create table material (
	class char(1) DEFAULT 'Q', item_id int PRIMARY KEY AUTO_INCREMENT, title VARCHAR(30), subclass int, cost double,
    FOREIGN KEY (class) REFERENCES classes(class)
);

create table movie (
	class char(1) DEFAULT 'G', item_id int PRIMARY KEY AUTO_INCREMENT, title VARCHAR(30) NOT NULL,
    format int, length time, last_watched date, keywords VARCHAR(100), imdb_score real,
    genre int, times_watched int, owned bool, cost double, FOREIGN KEY (class) REFERENCES classes(class)
);

create table music (
	class char(1) DEFAULT 'I', item_id int PRIMARY KEY AUTO_INCREMENT, title VARCHAR(30) NOT NULL, artist VARCHAR(30),
    ISBN VARCHAR(30), genre int, format int, cost double, FOREIGN KEY (class) REFERENCES classes(class)
);

create table painting (
	class char(1) DEFAULT 'R', item_id int PRIMARY KEY AUTO_INCREMENT, title VARCHAR(30), artist VARCHAR(30),
    painted date,  dimension VARCHAR(30), location VARCHAR(30), cost double, FOREIGN KEY (class) REFERENCES classes(class)
);

create table utensil (
	class char(1) DEFAULT 'N', item_id int PRIMARY KEY AUTO_INCREMENT, title VARCHAR(30) NOT NULL,
    cost double, FOREIGN KEY (class) REFERENCES classes(class)
);

create table workshop_tool (
	class char(1) DEFAULT 'Q', item_id int PRIMARY KEY AUTO_INCREMENT, title VARCHAR(30), specs VARCHAR(30),
    description VARCHAR(30), bought date, cost double, FOREIGN KEY (class) REFERENCES classes(class)
);




#helper relations
create table users ( user_id int NOT NULL PRIMARY KEY AUTO_INCREMENT, first_name varchar(30) NOT NULL, last_name varchar(30) NOT NULL, email varchar(30), phone_number varchar(30),
                     password varchar(200) NOT NULL, salt varchar(100), admin_id int DEFAULT 0, reg_date DATETIME DEFAULT NOW(), username varchar(30) NOT NULL, is_active int default 0
);

create table in_stock(
    class char(3), item_id int NOT NULL, user_id int NOT NULL, quantity int NOT NULL, quality int,
  PRIMARY KEY (item_id, user_id)
);

create table repair (
	class char(1) NOT NULL, item_id int PRIMARY KEY NOT NULL, repair_number int DEFAULT 0, cost real,
    completed date
);

create table maintenance (
	class char(1), item_id int PRIMARY KEY NOT NULL, tag int, description VARCHAR(30) NOT NULL,
    completed boolean DEFAULT FALSE, completed_date date, cost double
);

create table classes (
	class char(3) PRIMARY KEY NOT NULL, description VARCHAR(30) NOT NULL
);

create table login_attempts (user_id int NOT NULL, time time, attempts int default 0);
