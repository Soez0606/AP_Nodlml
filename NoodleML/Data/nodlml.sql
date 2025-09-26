PRAGMA foreign_keys = ON;

drop table if exists utilisateur;
drop table if exists classe;
drop table if exists etablissement;
drop table if exists role;

create table etablissement(
    num integer primary key autoincrement,
    nom text
);

create table classe(
    id integer primary key autoincrement,
    nom text,
    prof integer not null references utilisateur(email) on delete cascade,
    etablissement_num integer not null references etablissement(num) on delete cascade,
    chap_dispo integer
);

create table utilisateur(
    email text primary key,
    mot_de_passe text,
    nom text,
    prenom text,
    classe_id integer references classe(id) on delete cascade,
    role text not null references role(nom) on delete cascade
);

create table role(
    nom text primary key
);

insert into role(nom) values ('eleve');
insert into role(nom) values ('professeur');
insert into role(nom) values ('admin');

insert into etablissement(nom) values ('lycee altitude');

insert into utilisateur(email, mot_de_passe, nom, prenom, classe_id, role) values ('leoburban27@gmail.com', 'prof', 'Thomassin', 'gill', null, 'professeur');

insert into classe(nom, prof, etablissement_num, chap_dispo) values ('sio2', 'leoburban27@gmail.com', 1, 2);


insert into utilisateur(email, mot_de_passe, nom, prenom, classe_id, role) values ('joeylewis04032006@gmail.com', 'eleve', 'joey', 'lewis', 1, 'eleve');
insert into utilisateur(email, mot_de_passe, nom, prenom, classe_id, role) values ('sebastien.Marchand.ens@gmail.com', 'admin', 'marchand', 'sebastien', null, 'admin');