#!/bin/bash

SQLD=$(/usr/local/mysql/bin/mysql)
USERD=$(root)

echo What is the path to mysql.exe?
echo DEFAULT: "/usr/local/mysql/bin/mysql"
read -p 'Path: ' SQL
alias mysql=${SQL:-SQLD}

echo What is the username for your local MySQL instance?
echo DEFAULT: root
echo (Press enter to leave default setting.)
read -sp 'Username: ' USER

echo What is the password for your local MySQL instance?
echo DEFAULT: (none)
echo (Press enter to leave default setting.)

mysql -u ${USER:-USERD} -p -e "DROP USER IF EXISTS'gymflow'@'localhost';CREATE USER 'gymflow'@'localhost' IDENTIFIED BY 'GymTester12';GRANT ALL PRIVILEGES ON *.* TO 'gymflow'@'localhost' WITH GRANT OPTION;DROP DATABASE IF EXISTS gym;CREATE DATABASE gym;USE gym;DROP TABLE IF EXISTS CALORIES_MEAL;DROP TABLE IF EXISTS CALORIES;DROP TABLE IF EXISTS MEAL;DROP TABLE IF EXISTS USERS;CREATE TABLE USERS(USERNAME VARCHAR(32) UNIQUE NOT NULL,EMAIL VARCHAR(319) UNIQUE NOT NULL,PASSWORD TEXT NOT NULL,PRIMARY KEY(USERNAME));CREATE TABLE MEAL_LIST(ID INT AUTO_INCREMENT,ITEM VARCHAR(255),CALORIES INT NOT NULL,USERNAME VARCHAR(32) NOT NULL,DATE DATE NOT NULL,FOREIGN KEY(USERNAME) REFERENCES USERS(USERNAME),PRIMARY KEY(ID));SELECT * FROM USERS;SELECT * FROM MEAL_LIST;CREATE TABLE workout_preferences (id INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(255) NOT NULL,gender ENUM('male', 'female', 'other') NOT NULL,age INT NOT NULL,height INT NOT NULL,weight INT NOT NULL,exercise_level ENUM('beginner', 'intermediate', 'advanced') NOT NULL,goals TEXT NOT NULL,exercise_type VARCHAR(255) NOT NULL,preferred_location ENUM('gym', 'home', 'outdoors') NOT NULL,exercise_duration INT NOT NULL,exercise_list JSON,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);"

pause