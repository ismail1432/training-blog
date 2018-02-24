DROP DATABASE IF EXISTS blog;
CREATE DATABASE blog;
USE blog;

DROP TABLE IF EXISTS Article;
CREATE TABLE Article (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  subTitle VARCHAR(100),
  content TEXT NOT NULL,
  publicationDate DATETIME NOT NULL
)ENGINE=InnoDB;