`````sql

CREATE DATABASE IF NOT EXISTS parking_DB;

USE parking_DB;

-- database tables

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  pass varchar(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Categories (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  type VARCHAR(100) NOT NULL UNIQUE,
  parking_fee DOUBLE NOT NULL
);
CREATE TABLE IF NOT EXISTS Vehicles (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  license_plate VARCHAR(20) NOT NULL UNIQUE,
  model VARCHAR(100),
  category_id int,
  created_by int,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS inputs (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  inputted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  link_code varchar(255) NOT NULL,
  vehicle_id INT
);
CREATE TABLE IF NOT EXISTS inputs_history (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  inputted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  link_code varchar(255) NOT NULL,
  vehicle_id INT
);
CREATE TABLE IF NOT EXISTS outputs (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  outputted_at VARCHAR(255),
  vehicle_id INT,
  permanence_time varchar(255) NOT NULL,
  input_link_code varchar(255) NOT NULL,
  final_price DOUBLE NOT NULL
);

CREATE TABLE IF NOT EXISTS outputs_history (
  id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
  outputted_at VARCHAR(255),
  permanence_time varchar(255) NOT NULL,
  vehicle_id INT,
  input_link_code varchar(255) NOT NULL,
  final_price DOUBLE NOT NULL
);

CREATE TABLE IF NOT EXISTS vehicle_has_images (
	image_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
	image_name VARCHAR(255),
  path VARCHAR (255),
  vehicle_id INT,
  inserted_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- constraints added

ALTER TABLE vehicle_has_images 
ADD CONSTRAINT FOREIGN KEY (vehicle_id) REFERENCES vehicles(id)

ALTER TABLE vehicles 
ADD CONSTRAINT FOREIGN KEY (category_id) REFERENCES categories(id);


ALTER TABLE inputs_history 
ADD CONSTRAINT FOREIGN KEY (vehicle_id) REFERENCES vehicles(id);

ALTER TABLE outputs_history
ADD CONSTRAINT FOREIGN KEY (vehicle_id) REFERENCES vehicles(id);

alter table vehicles 
ADD CONSTRAINT FOREIGN KEY (created_by) REFERENCES users(id);

ALTER TABLE inputs 
ADD CONSTRAINT FOREIGN KEY (vehicle_id) REFERENCES Vehicles(id);

ALTER TABLE outputs 
ADD CONSTRAINT FOREIGN KEY (vehicle_id) REFERENCES Vehicles(id);

-- trigger for create an input history for search data

CREATE TRIGGER insert_inputs_history AFTER INSERT ON inputs
FOR EACH ROW
BEGIN
    INSERT INTO inputs_history (vehicle_id, link_code) 
    VALUES (NEW.vehicle_id, NEW.link_code);
END;

CREATE TRIGGER insert_outputs_history AFTER INSERT ON outputs
FOR EACH ROW
BEGIN
    INSERT INTO outputs_history (vehicle_id, final_price, permanence_time, input_link_code, outputted_at) 
    VALUES (NEW.vehicle_id, NEW.final_price, NEW.permanence_time, NEW.input_link_code, NEW.outputted_at);
    DELETE FROM inputs
    WHERE vehicle_id = NEW.vehicle_id ;
END;