CREATE DATABASE sae_maintenance;

USE sae_maintenance;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('enfant', 'enseignant', 'parent') NOT NULL
);

CREATE TABLE exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_type VARCHAR(50) NOT NULL,
    score INT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE user_relationships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT,
    teacher_id INT,
    child_id INT NOT NULL,
    FOREIGN KEY (parent_id) REFERENCES users(id),
    FOREIGN KEY (teacher_id) REFERENCES users(id),
    FOREIGN KEY (child_id) REFERENCES users(id)
);