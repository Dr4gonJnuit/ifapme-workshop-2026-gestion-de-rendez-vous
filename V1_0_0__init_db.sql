-- wrkshop_gestionRdv

CREATE TABLE IF NOT EXISTS "public"."client" (
    "id" UUID PRIMARY KEY,
    "firstname" VARCHAR(255) NOT NULL,
    "lastname" VARCHAR(255) NOT NULL,
    "email" VARCHAR(255) UNIQUE,
    "phone" VARCHAR(20) UNIQUE
);

CREATE TABLE IF NOT EXISTS "public"."prestataire" (
    "id" UUID PRIMARY KEY,
    "profession" VARCHAR(255) NOT NULL,
    "firstname" VARCHAR(255) NOT NULL,
    "lastname" VARCHAR(255) NOT NULL,
    "email" VARCHAR(255) UNIQUE,
    "phone" VARCHAR(20) UNIQUE
);

CREATE TABLE IF NOT EXISTS "public"."utilisateur" (
    "id" UUID PRIMARY KEY,
    "username" VARCHAR(255) NOT NULL UNIQUE,
    "password" VARCHAR(255) NOT NULL,
    "email" VARCHAR(255) UNIQUE
);

CREATE TABLE IF NOT EXISTS "public"."status" (
    "id" UUID PRIMARY KEY,
    "name" VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS "public"."rdv" (
    "id" UUID PRIMARY KEY,
    "date" TIMESTAMP NOT NULL,
    "client_id" UUID NOT NULL,
    "prestataire_id" UUID NOT NULL,
    "user_id" UUID NOT NULL,
    "status" UUID NOT NULL,
    FOREIGN KEY (client_id) REFERENCES client(id),
    FOREIGN KEY (prestataire_id) REFERENCES prestataire(id),
    FOREIGN KEY (user_id) REFERENCES utilisateur(id),
    FOREIGN KEY (status) REFERENCES status(id)
);
