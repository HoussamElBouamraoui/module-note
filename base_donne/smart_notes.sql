CREATE DATABASE IF NOT EXISTS smart_notes;
USE smart_notes;

-- Table admin
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Table student
CREATE TABLE student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    id_admin INT,
    FOREIGN KEY (id_admin) REFERENCES admin(id) ON DELETE SET NULL
);

-- Table modules (chaque étudiant a ses propres modules)
CREATE TABLE modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    desciption TEXT,
    id_student INT NOT NULL,
    FOREIGN KEY (id_student) REFERENCES student(id) ON DELETE CASCADE
);

-- Table notes note liee à un module et un etudiant
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commentaire TEXT,
    id_student INT NOT NULL,
    id_module INT NOT NULL,
    FOREIGN KEY (id_student) REFERENCES student(id) ON DELETE CASCADE,
    FOREIGN KEY (id_module) REFERENCES modules(id) ON DELETE CASCADE
);
-- tables pour les sessions
CREATE TABLE `sessions` (
  `session_id` VARCHAR(128) NOT NULL PRIMARY KEY,
  `session_data` TEXT NOT NULL,
  `last_access` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);