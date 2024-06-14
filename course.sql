CREATE TABLE categorie (
    id SERIAL PRIMARY KEY,
    nomcategorie VARCHAR(30)
);

CREATE TABLE coureur_categories (
    coureur_id INT NOT NULL,
    categorie_id INT NOT NULL,
    PRIMARY KEY (coureur_id, categorie_id)
);

-- CREATE INDEX categorie_id ON coureur_categories (categorie_id);

CREATE TABLE coureurcategorie (
    idcoureur INT,
    categorie VARCHAR(30)
);

CREATE TABLE coureuretape (
    id SERIAL PRIMARY KEY,
    etape VARCHAR(30),
    coureur VARCHAR(30)
);

-- CREATE INDEX idetape ON coureuretape (etape);

CREATE TABLE coureurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(30),
    categorie VARCHAR(30),
    numerodossard VARCHAR(30),
    genre VARCHAR(30),
    datedenaissance DATE,
    equipe VARCHAR(30),
    CONSTRAINT nom_unique UNIQUE (nom, numerodossard)
);

CREATE TABLE etapes (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(30),
    longueurkm varchar(30),
    nbcoureurparequipe INT,
    rangetape INT,
    heuredepart TIME,
    datedepart DATE
);

CREATE TABLE points (
    classement SERIAL PRIMARY KEY,
    points INT
);

CREATE TABLE points_par_etape (
    id SERIAL PRIMARY KEY,
    idcoureur VARCHAR(30),
    idetape VARCHAR(30),
    points INT
);

-- CREATE INDEX idcoureur ON points_par_etape (idcoureur);
-- CREATE INDEX idetape ON points_par_etape (idetape);

CREATE TABLE profils (
    id SERIAL PRIMARY KEY,
    role VARCHAR(30),
    nom VARCHAR(30),
    pwd VARCHAR(30),
    CONSTRAINT nom_unique_profil UNIQUE (nom, role),
    CONSTRAINT nom_pwd_unique UNIQUE (nom, role, pwd)
);

CREATE TABLE resultats (
    id SERIAL PRIMARY KEY,
    rangetape INT,
    coureur VARCHAR(30),
    genre VARCHAR(30),
    datedenaissance DATE,
    equipe VARCHAR(30),
    arrivee TIMESTAMP,
    numero INT,
    penalite TIME DEFAULT '00:00:00' NOT NULL,
    points INT DEFAULT 0,
    CONSTRAINT rangetape_unique UNIQUE (rangetape, numero, coureur)
);

CREATE TABLE temps (
    id SERIAL PRIMARY KEY,
    lieu VARCHAR(30),
    coureur VARCHAR(30),
    heurearrivee TIMESTAMP,
    penalite TIME DEFAULT '00:00:00'

);

CREATE OR REPLACE VIEW coureurs_temps AS
SELECT
   r.id,
    r.equipe,
    r.genre,
    r.coureur,
    r.arrivee,
    r.penalite,
    e.datedepart,
    e.heuredepart,
    (r.arrivee - (e.datedepart + e.heuredepart::time)) + (r.penalite::interval) AS temps_total

FROM
    resultats r
JOIN
    etapes e ON r.rangetape = e.id;

CREATE OR REPLACE VIEW v_classement AS
SELECT
    eta.nom,
    eta.longueurkm,
    eta.datedepart,
    eta.heuredepart,
    c.categorie,
    res.id,
    res.rangetape,
    res.coureur,
    res.genre,
    res.datedenaissance,
    res.equipe,
    res.arrivee,
    res.numero,
    res.penalite,
    res.points,
    ((res.arrivee - (eta.datedepart::timestamp + eta.heuredepart::time)) + res.penalite::interval) AS tempschrono

FROM
    resultats res
LEFT JOIN
    coureurs c ON c.nom = res.coureur
LEFT JOIN
    etapes eta ON eta.id = res.rangetape;

CREATE OR REPLACE VIEW v_coureurs AS
SELECT
    c.id,
    c.nom,
    cat.nomcategorie,
    c.numerodossard,
    c.genre,
    c.datedenaissance,
    c.equipe
FROM
    coureurs c
LEFT JOIN
    categorie cat ON cat.nomcategorie = c.categorie;

CREATE OR REPLACE VIEW v_etapecoureurs AS
SELECT
    et.nom AS Lieucourse,
    et.datedepart,
    et.heuredepart,
    tem.heurearrivee,
    tem.penalite,
    (tem.heurearrivee::time - et.heuredepart::time + tem.penalite::interval) AS chrono,
    c.nom AS coureur,
    cat.nomcategorie,
    et.longueurkm,
    et.nbcoureurparequipe,
    et.rangetape,
    c.numerodossard,
    c.genre,
    c.datedenaissance,
    pr.nom AS equipe
FROM
    coureuretape ce
LEFT JOIN
    coureurs c ON c.nom = ce.coureur
LEFT JOIN
    etapes et ON et.nom = ce.etape
LEFT JOIN
    categorie cat ON cat.nomcategorie = c.categorie
LEFT JOIN
    profils pr ON pr.nom = c.equipe
LEFT JOIN
    temps tem ON tem.lieu = ce.etape;
