# hSign PHP SDK

Un client API PHP léger, rapide et **sans dépendances externes** (pas de Guzzle, uniquement du cURL natif) pour piloter l'instance hSign (`sign.hcloud.fr`) de hCloud, basé sur l'API v2 de Documenso.

## 🚀 Fonctionnalités

- **Zéro dépendance** : Empreinte mémoire minimale, aucun risque de conflit de version avec d'autres packages.
- **Syntaxe Fluide** : Enchaînement de méthodes (`Method Chaining`) intuitif et propre.
- **Gestion des Templates** : Envoi et distribution de documents à partir de vos modèles préconfigurés.
- **Champs Pré-remplis** : Remplissage dynamique des champs avant envoi (textes, cases à cocher, dates, etc.).
- **Surcharges Avancées (Overrides)** : Personnalisation complète de l'expérience de signature (titre de l'enveloppe, objet/corps du mail, langue, activation/désactivation des modes de signature, URL de redirection après signature...).

## 📦 Installation

Ajoutez le package à votre projet via Composer :