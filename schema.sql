CREATE DATABASE IF NOT EXISTS gym;
USE gym;

-- Drop tables if they exist
DROP TABLE IF EXISTS CALORIES_MEAL;
DROP TABLE IF EXISTS CALORIES;
DROP TABLE IF EXISTS MEAL;
DROP TABLE IF EXISTS USERS;
DROP TABLE IF EXISTS workout_preferences;

-- Create USERS table
CREATE TABLE USERS
(
    USERNAME VARCHAR(32) UNIQUE NOT NULL,
    EMAIL VARCHAR(319) UNIQUE NOT NULL,
    PASSWORD TEXT NOT NULL,
    PRIMARY KEY(USERNAME)
);

-- Create CALORIES table
CREATE TABLE MEAL_LIST
(
    ID INT AUTO_INCREMENT,
    ITEM VARCHAR(255),
    CALORIES INT NOT NULL,
    USERNAME VARCHAR(32) NOT NULL,
    DATE DATE NOT NULL,
    FOREIGN KEY(USERNAME) REFERENCES USERS(USERNAME),
    PRIMARY KEY(ID)
);

CREATE TABLE workout_preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    age INT NOT NULL,
    height INT NOT NULL,
    weight INT NOT NULL,
    exercise_level ENUM('beginner', 'intermediate', 'advanced') NOT NULL,
    goals TEXT NOT NULL,
    exercise_type VARCHAR(255) NOT NULL,
    preferred_location ENUM('gym', 'home', 'outdoors') NOT NULL,
    exercise_duration INT NOT NULL,
    exercise_list JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);