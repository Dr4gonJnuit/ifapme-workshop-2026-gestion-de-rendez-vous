# Devoir : Gestion de rendez-vous

## Objectif du projet

Développer une API REST permettant :

- La gestion des clients
- La gestion des rendez-vous
- La consultation des rendez-vous
- Une gestion simple des disponibilités

## Fonctionnalités attendues

1. Gestion des clients

Un client doit pouvoir :

- Être enregistré dans le système
- Être consulté
- Être listé

Informations minimales attendues :

- Nom
- Prénom
- Email
- Téléphone

---

2. Gestion des rendez-vous

Un rendez-vous doit contenir au minimum :

- Une date
- Une heure
- Un client
- Un statut

Statuts possibles (suggestion) :

- PLANIFIE
- ANNULE
- TERMINE

---

3. Prise de rendez-vous

L’application doit permettre :

- La création d’un rendez-vous
- L’annulation d’un rendez-vous
- La consultation des rendez-vous (par date, par client ou autre critère pertinent)

## Points à analyser

Le client n’a pas précisé certains éléments importants, notamment :

- La durée d’un rendez-vous
- Les horaires d’ouverture
- Le nombre de professionnels disponibles
- La gestion des conflits horaires
- Les règles d’annulation
- Le délai minimum avant annulation
- La gestion précise des disponibilités

Ces éléments doivent être :

- Soit clarifiés auprès du client (enseignant)
- Soit définis par l’équipe sous forme d’hypothèses

Toute hypothèse devra être explicitement documentée dans le README.

## Contraintes métier minimales

- Un rendez-vous ne peut pas être planifié dans le passé.
- Un créneau déjà occupé ne peut pas être réservé (sauf justification contraire).
- Un client ne peut pas avoir deux rendez-vous simultanés.

D’autres règles métier peuvent être ajoutées si elles sont cohérentes et justifiées.

## Contraintes techniques

- Architecture en couches (Controller, Service, Repository).
- Utilisation de JPA pour la persistance.
- Modélisation cohérente des relations entre entités.
- Implémentation des règles métier dans la couche Service.

## Attentes

L’application doit être :

- Fonctionnelle
- Structurée
- Cohérente
- Justifiée techniquement

L’évaluation portera autant sur la qualité de l’analyse du besoin que sur la qualité de l’implémentation.

Le projet est a réalisé par groupe de 3 et sera a présenter le 3 mars.
