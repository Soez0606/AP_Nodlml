PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE etablissement(
    num integer primary key autoincrement,
    nom text
);
INSERT INTO etablissement VALUES(1,'lycee altitude');
INSERT INTO etablissement VALUES(2,'lycee dominique villard');
CREATE TABLE classe(
    id integer primary key autoincrement,
    nom text,
    prof integer not null references utilisateur(email) on delete cascade,
    etablissement_num integer not null references etablissement(num) on delete cascade,
    chap_dispo integer
);
INSERT INTO classe VALUES(1,'sio2','leoburban27@gmail.com',1,2);
INSERT INTO classe VALUES(2,'sio1','leoburban27@gmail.com',2,5);
CREATE TABLE utilisateur(
    email text primary key,
    mot_de_passe text,
    nom text,
    prenom text,
    classe_id integer references classe(id) on delete cascade,
    role text not null references role(nom) on delete cascade
);
INSERT INTO utilisateur VALUES('leoburban27@gmail.com','prof','Thomassin','gill',NULL,'professeur');
INSERT INTO utilisateur VALUES('joeylewis04032006@gmail.com','eleve','joey','lewis',1,'eleve');
INSERT INTO utilisateur VALUES('sebastien.Marchand.ens@gmail.com','admin','marchand','sebastien',NULL,'admin');
CREATE TABLE role(
    nom text primary key
);
INSERT INTO role VALUES('eleve');
INSERT INTO role VALUES('professeur');
INSERT INTO role VALUES('admin');
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('etablissement',2);
INSERT INTO sqlite_sequence VALUES('classe',2);
COMMIT;
