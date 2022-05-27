USE blogphp;

SHOW TABLES;
SELECT * FROM article;
SELECT * FROM category;
SELECT * FROM comment;
SELECT * FROM user;


SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE article;
TRUNCATE TABLE category;
TRUNCATE TABLE comment;
TRUNCATE TABLE user;
SET FOREIGN_KEY_CHECKS = 1;


INSERT INTO user(lastname,firstname,email,hash,role,createdAt) 
	VALUES ('Dupont','Daniel','duda@pp.fr','$2y$10$EWqVlhIBEHHfk9Zdv8MMfuNquJZkSgKJpgNn3BRym.LiMw86wJjtS','USER','2022-04-20 11:10:12');
INSERT INTO user(lastname,firstname,email,hash,role,createdAt) 
	VALUES ('Gaston','Lagaffe','gL@gm.com','$2y$10$EWqVlhIBEHHfk9Zdv8MMfuNquJZkSgKJpgNn3BRym.LiMw86wJjtS','ADMIN','2022-04-23 11:10:12');

INSERT INTO category(label) 
	VALUES ('Actualités');

INSERT INTO article(title,abstract,content,image,fkUserId,fkCategoryId,createdAt) 
	VALUES ('Tollenda','Tollenda est atque extrahenda radicitus. Contemnit enim disserendi elegantiam, confuse loquitur.','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nemo igitur esse beatus potest. Laboro autem non sine causa; Duo Reges: constructio interrete. Ita prorsus, inquam; Quam si explicavisset, non tam haesitaret. ','image2.jpg',1,1,'2022-05-11 12:10:25');
	
INSERT INTO article(title,abstract,content,image,fkUserId,fkCategoryId,createdAt) 
	VALUES ('Addidisti','Addidisti ad extremum etiam indoctum fuisse.',' Addidisti ad extremum etiam indoctum fuisse. Duo Reges: constructio interrete. Tubulo putas dicere? Quam ob rem tandem, inquit, non satisfacit? Tum Piso: Quoniam igitur aliquid omnes, quid Lucius noster?','image3.jpg',1,2,'2022-05-12 11:10:25');
	
	