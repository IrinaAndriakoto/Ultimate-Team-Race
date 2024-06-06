-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Sam 01 Juin 2024 à 07:54
-- Version du serveur :  5.6.20-log
-- Version de PHP :  5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";



CREATE TABLE IF NOT EXISTS `categorie` (
`id` int(11) NOT NULL,
  `nomcategorie` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `coureurs` (
`id` int(11) NOT NULL,
  `nom` varchar(30) DEFAULT NULL,
  `idcategorie` int(11) DEFAULT NULL,
  `numerodossard` varchar(30) DEFAULT NULL,
  `genre` varchar(30) DEFAULT NULL,
  `datedenaissance` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `etapes` (
`id` int(11) NOT NULL,
  `nom` varchar(30) DEFAULT NULL,
  `longueurkm` double DEFAULT NULL,
  `nbcoureurparequipe` int(11) DEFAULT NULL,
  `rangetape` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `profils` (
`id` int(11) NOT NULL,
  `role` varchar(30) DEFAULT NULL,
  `nom` varchar(30) DEFAULT NULL,
  `pwd` varchar(30) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `categorie`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `coureurs`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `idcategorie` (`idcategorie`);

ALTER TABLE `etapes`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `profils`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `categorie`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `coureurs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `etapes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `profils`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


insert into categorie(nomcategorie) values('Homme Junior'),('Femme Junior'),('Homme Senior'),('Femme Senior');
insert into profils(role,nom,pwd) values('admin','admin','admin123'),('equipe','Rouge','rouge123'),('equipe','Bleue','bleue123');
insert into etapes(nom,longueurkm,nbcoureurparequipe,rangetape) values('Betsizaraina',23.2,3,1),('Digue Fenoarivo',6.7,1,2),('Ampasimbe',11,2,3);
alter table coureurs add column idequipe int ;
update categorie set nomcategorie ='Senior' where id=2;
delete from categorie where id>=3;
truncate table coureurs;

insert into coureurs(nom,idcategorie,numerodossard,genre,datedenaissance,idequipe) values('Charles',1,45,'Homme','2006-12-12',2),('Victor',2,12,'Homme','1999-03-04',2),('Emma',2,22,'Femme','2000-07-31',2),('Emily',1,30,'Femme','2006-02-05',2);

create table coureuretape ( id int auto_increment primary key , idetape int , idcoureur int , foreign key(idetape) references etapes(id) , foreign key(id) references coureurs(id));
insert into coureuretape(idetape,idcoureur) values(1,2);
create view v_etapecoureurs as select et.nom as Lieucourse,c.nom as coureur,cat.nomcategorie,et.longueurkm,et.nbcoureurparequipe,et.rangetape,c.numerodossard,c.genre,c.datedenaissance,pr.nom from coureuretape as ce left join coureurs c on c.id=ce.idcoureur left join etapes et on et.id=ce.idetape left join categorie cat on cat.id=c.idcategorie left join profils pr on pr.id=c.idequipe group by Lieucourse;
select * from coureurs where idequipe=2;
insert into coureurs(nom,idcategorie,numerodossard,genre,datedenaissance,idequipe) values('Rakoto',2,22,'Homme','1999-03-01',3),('Rasoa',1,70,'Femme','2007-01-16',3),('Mahefa',1,11,'Homme','2005-12-12',3);
select * from v_coureurs where equipe='rouge';
drop view v_coureurs;
create view v_coureurs as select c.id,c.nom,cat.nomcategorie,c.numerodossard,c.genre,c.datedenaissance,pr.nom as equipe from coureurs c left join categorie cat on cat.id=c.idcategorie left join profils pr on pr.id=c.idequipe;
create view v_etapecoureurs as select et.nom as Lieucourse,c.nom as coureur,cat.nomcategorie,et.longueurkm,et.nbcoureurparequipe,et.rangetape,c.numerodossard,c.genre,c.datedenaissance,pr.nom from coureuretape as ce left join coureurs c on c.id=ce.idcoureur left join etapes et on et.id=ce.idetape left join categorie cat on cat.id=c.idcategorie left join profils pr on pr.id=c.idequipe group by Lieucourse;
insert into coureuretape (idetape,idcoureur) values(1,1);
select * from v_etapecoureurs  ;
drop view v_etapecoureurs;
update etapes set heuredepart='14:30:00' where id=3;
create view v_etapecoureurs as select et.nom as Lieucourse,et.heuredepart,c.nom as coureur,cat.nomcategorie,et.longueurkm,et.nbcoureurparequipe,et.rangetape,c.numerodossard,c.genre,c.datedenaissance,pr.nom from coureuretape as ce left join coureurs c on c.id=ce.idcoureur left join etapes et on et.id=ce.idetape left join categorie cat on cat.id=c.idcategorie left join profils pr on pr.id=c.idequipe ;
create table temps(id int auto_increment primary key, lieu varchar(30),coureur varchar(30) , temps time );
alter table temps add column penalite time default '00:00:00' ;
select et.nom as Lieucourse,et.heuredepart,te.heurearrivee,c.nom as coureur,cat.nomcategorie,et.longueurkm,et.nbcoureurparequipe,et.rangetape,c.numerodossard,c.genre,c.datedenaissance,pr.nom from coureuretape as ce left join coureurs c on c.id=ce.idcoureur left join etapes et on et.id=ce.idetape left join categorie cat on cat.id=c.idcategorie left join profils pr on pr.id=c.idequipe left join temps te on te.lieu=et.nom;
drop table classement;
CREATE TABLE classement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lieucourse VARCHAR(255),
    coureur VARCHAR(255),
    temps TIME,
    rang INT,
    points INT
);
select c.nom as coureur,cat.nomcategorie as categorie,eta.nom as lieu,eta.longueurkm,c.numerodossard,eta.heuredepart,t.heurearrivee,addtime(t.heurearrivee,penalite) as heurearrivee_penalite,subtime(t.heurearrivee,eta.heuredepart) as tempstotal from coureuretape ce left join etapes eta on eta.id=ce.idetape left join coureurs c on c.id=ce.idcoureur left join categorie cat on cat.id=c.idcategorie  left join temps t on t.coureur=c.nom ;
create table classement(id int auto_increment primary key,idetape int,idcoureur int , rang int,points int,foreign key(idetape) references etapes(id),foreign key(idcoureur) references coureurs(id));
select * from temps where lieu='betsizaraina';
truncate classement;
update etapes set nom='Fenoarivo' where id=2;
create table points_par_etape(id int auto_increment primary key,idcoureur int,idetape int , points int , foreign key(idcoureur) references coureurs(id) , foreign key(idetape) references etapes(id));

create table v_classement select eta.nom,eta.datedepart,eta.heuredepart,c.categorie,res.* from resultats res left join coureurs c on c.nom = res.coureur left join etapes eta on eta.rangetape=res.rangetape;
CREATE VIEW v_coureurs AS select `c`.`id` AS `id`,`c`.`nom` AS `nom`,`cat`.`nomcategorie` AS `nomcategorie`,`c`.`numerodossard` AS `numerodossard`,`c`.`genre` AS `genre`,`c`.`datedenaissance` AS `datedenaissance`,`c`.`equipe` AS `equipe` from ((`course`.`coureurs` `c` left join `course`.`categorie` `cat` on((`cat`.`nomcategorie` = `c`.`categorie`))) left join `course`.`profils` `pr` on((`pr`.`nom` = `c`.`equipe`)));
create view v_etapecoureurs as select `et`.`nom` AS `Lieucourse`,`et`.`datedepart` AS `datedepart`,`et`.`heuredepart` AS `heuredepart`,`tem`.`heurearrivee` AS `heurearrivee`,`tem`.`penalite` AS `penalite`,(subtime(`tem`.`heurearrivee`,`et`.`heuredepart`) + `tem`.`penalite`) AS `chrono`,`c`.`nom` AS `coureur`,`cat`.`nomcategorie` AS `nomcategorie`,`et`.`longueurkm` AS `longueurkm`,`et`.`nbcoureurparequipe` AS `nbcoureurparequipe`,`et`.`rangetape` AS `rangetape`,`c`.`numerodossard` AS `numerodossard`,`c`.`genre` AS `genre`,`c`.`datedenaissance` AS `datedenaissance`,`pr`.`nom` AS `equipe` from (((((`course`.`coureuretape` `ce` left join `course`.`coureurs` `c` on((`c`.`nom` = `ce`.`coureur`))) left join `course`.`etapes` `et` on((`et`.`nom` = `ce`.`etape`))) left join `course`.`categorie` `cat` on((`cat`.`nomcategorie` = `c`.`categorie`))) left join `course`.`profils` `pr` on((`pr`.`nom` = `c`.`equipe`))) left join `course`.`temps` `tem` on((`tem`.`lieu` = `ce`.`etape`)))


