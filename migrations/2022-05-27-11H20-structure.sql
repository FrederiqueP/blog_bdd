SHOW databases;

CREATE DATABASE blogphp
CHARACTER SET utf8
COLLATE utf8_general_ci;

USE blogphp;

-- CREATE USER nomutilisateur@localhost IDENTIFIED BY motdepasse;

USE blogphp;

DROP TABLE IF EXISTS Comment;
DROP TABLE IF EXISTS Article;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS User;


CREATE TABLE User(
   idUser 	int(3) unsigned NOT NULL AUTO_INCREMENT,
   lastname 	varchar(128)NOT NULL,
   firstname 	varchar(128)NOT NULL,
   email 	varchar(128)NOT NULL UNIQUE,
   hash 	varchar(128)NOT NULL,
   role  	varchar(128)NOT NULL,
   createdAt 	DATETIME,
   PRIMARY KEY (idUser)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Category(
   idCategory 	int(3) unsigned NOT NULL AUTO_INCREMENT,
   label	varchar(128)NOT NULL,
   PRIMARY KEY (idCategory)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE Article
(
   idArticle INT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
   title VARCHAR(128) NOT NULL,
   abstract VARCHAR(512),
   content VARCHAR(20000),
   image VARCHAR(128),
   fkUserId INT(3) UNSIGNED NOT NULL,
	CONSTRAINT fk_user
		FOREIGN KEY (fkUserId)
		REFERENCES User(idUser),
   fkCategoryId INT(3) UNSIGNED NOT NULL,
	CONSTRAINT fk_category
		FOREIGN KEY (fkCategoryId )
		REFERENCES Category(idCategory ),
	createdAt DATETIME,
	PRIMARY KEY (idArticle)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE Comment
(
	idComment INT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
	content VARCHAR(1000) NOT NULL,
        createdAt DATETIME,
	fkArticleId INT(3) UNSIGNED NOT NULL,
	CONSTRAINT fk_article
		FOREIGN KEY (fkArticleId)
		REFERENCES Article(idArticle),
	fkUserId INT(3) UNSIGNED NOT NULL,
	CONSTRAINT fk_usercom
		FOREIGN KEY (fkUserId)
		REFERENCES User(idUser),
	PRIMARY KEY (idComment),
	INDEX (createdAt)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

SHOW TABLES;


ALTER TABLE `comment`
  DROP FOREIGN KEY `fk_article`; 
  
ALTER TABLE `comment`
ADD CONSTRAINT `fk_article` 
FOREIGN KEY (`fkArticleId`) 
REFERENCES `article` (`idArticle`) 
ON DELETE CASCADE 
ON UPDATE CASCADE;