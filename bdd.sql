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

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `role`) VALUES
(9, 'Enfant', 'exemple', 'enfant.exemple@hotmail.com', '$2y$10$liwxrxSgT97Orej2EuKWXer19KGYG0fM6wTg.R4WVkV7IwMpiDYcO', 'enfant'),
(10, 'Parent', 'exemple', 'Parent.exemple@gmail.com', '$2y$10$1RmjC3tDNSSbivUqORdkBOF.f2JmIMqp8in6vTTVccQT5w9xdxDeC', 'parent'),
(11, 'Enseignant', 'exemple', 'Enseignant.exemple@hotmail.fr', '$2y$10$OYjaOkbUC2MrbhEzYdBVCOvBpH0/RNp2e2mwqB06prfSGE2lDB4Xy', 'enseignant');


CREATE TABLE exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_type VARCHAR(50) NOT NULL,
    score INT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO `exercises` (`id`, `user_id`, `exercise_type`, `score`, `date`) VALUES
(61, 9, 'addition', 6, '2025-03-09 13:08:20'),
(62, 9, 'dictee', 8, '2025-03-09 13:09:26');

CREATE TABLE user_relationships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT,
    teacher_id INT,
    child_id INT NOT NULL,
    FOREIGN KEY (parent_id) REFERENCES users(id),
    FOREIGN KEY (teacher_id) REFERENCES users(id),
    FOREIGN KEY (child_id) REFERENCES users(id)
);

INSERT INTO `user_relationships` (`id`, `parent_id`, `teacher_id`, `child_id`) VALUES
(22, 10, NULL, 9),
(23, NULL, 11, 9);