![separe](https://github.com/studoo-app/.github/blob/main/profile/studoo-banner-logo.png)
# Symfony TP 5 : Formulaires et Validation
[![Version](https://img.shields.io/badge/Version-2025-blue)]()
[![Niveau](https://img.shields.io/badge/Niveau-SIO2-yellow)]()

## 🎯 Contexte professionnel

Vous êtes développeur web dans une startup EdTech nommée **SkillBoost Academy**. L'entreprise souhaite créer une plateforme innovante de formation en ligne permettant aux formateurs de proposer leurs cours et aux apprenants de s'inscrire facilement.

Le système doit gérer :

- **Formations** avec leurs détails, programmes et tarifs
- **Utilisateurs** (formateurs et apprenants)
- **Inscriptions** des apprenants aux formations
- **Catégories** de formation pour faciliter la recherche

Votre mission : développer les **interfaces de saisie avec formulaires et validation** en utilisant le système de formulaires Symfony avec des contraintes de validation personnalisées pour garantir la qualité des données éducatives.

## 📋 Objectifs pédagogiques

**Compétences techniques visées :**

- Maîtriser le système de formulaires Symfony (FormBuilder, FormType)
- Implémenter la validation avancée avec contraintes personnalisées (relativement simple, juste pour démontrer leur existence et utilisation)

**Compétences transversales :**

- Concevoir des formulaires intuitifs adaptés au contexte éducatif
- Structurer le code selon les bonnes pratiques Symfony
- Garantir la qualité et la cohérence des données métier

## 🛠️ Consignes détaillées

### 🚀 Phase 1 : Modélisation et Entités de Base (60 minutes)

#### Étape 1.1 : Préparation du projet

Créez un nouveau projet Symfony et installez les dépendances :

```bash
symfony new symfony-tp5-formulaires --webapp
```

Configuration de la base de données dans `.env` :

```
DATABASE_URL="mysql://root:@127.0.0.1:3306/skillboost_academy?serverVersion=8.0"
```

#### Étape 1.2 : Création des entités principales

Créez les quatre entités de base avec la commande `make:entity`.

**Entité `Categorie`** :

```bash
symfony console make:entity Categorie
```

Ajoutez les propriétés suivantes :

- `nom` : string, 100 caractères
- `slug` : string, 100 caractères (version URL-friendly du nom)
- `description` : text, nullable
- `couleur` : string, 7 caractères (format hex #RRGGBB)
- `icone` : string, 50 caractères

**Entité `User`** :

```bash
symfony console make:entity User
```

Ajoutez les propriétés suivantes :

- `email` : string, 180 caractères, unique
- `roles` : json (stocke un tableau de rôles)
- `password` : string, 255 caractères
- `prenom` : string, 100 caractères
- `nom` : string, 100 caractères
- `telephone` : string, 20 caractères, nullable
- `dateNaissance` : date, nullable
- `entreprise` : string, 255 caractères, nullable
- `poste` : string, 100 caractères, nullable
- `bio` : text, nullable
- `photoProfil` : string, 255 caractères, nullable
- `dateCreation` : datetime
- `estActif` : boolean

**Entité `Formation`** :

```bash
symfony console make:entity Formation
```

Ajoutez les propriétés suivantes :

- `titre` : string, 250 caractères
- `slug` : string, 250 caractères
- `description` : text
- `programme` : text
- `objectifs` : text, nullable
- `prerequis` : text, nullable
- `duree` : integer (durée en heures)
- `niveau` : string, 50 caractères (débutant, intermédiaire, avancé)
- `prix` : decimal (10,2)
- `capaciteMax` : integer
- `modalite` : string, 50 caractères (présentiel, distanciel, hybride)
- `dateDebut` : date
- `dateFin` : date
- `estPublie` : boolean
- `dateCreation` : datetime

**Entité `Inscription`** :

```bash
symfony console make:entity Inscription
```

Ajoutez les propriétés suivantes :

- `dateInscription` : datetime
- `statut` : string, 50 caractères (en_attente, confirmee, annulee, terminee)
- `modePaiement` : string, 50 caractères, nullable (carte, virement, cheque)
- `commentaire` : text, nullable (besoins spécifiques de l'apprenant)
- `noteSatisfaction` : integer, nullable (de 1 à 5)
- `commentaireSatisfaction` : text, nullable

#### Étape 1.3 : Ajout des relations et migration de la structure de base de données

Modifiez l'entité `Formation` pour ajouter les relations :

```bash
symfony console make:entity Formation
```

**Ajoutez les relations bidirectionnelles suivantes :**

- `categorie` : relation ManyToOne vers Categorie
- `formateur` : relation ManyToOne vers User (le formateur)

Modifiez l'entité `Inscription` pour ajouter les relations :

```bash
symfony console make:entity Inscription
```

**Ajoutez les relations bidirectionnelles suivantes :**

- `formation` : relation ManyToOne vers Formation
- `apprenant` : relation ManyToOne vers User (l'apprenant)

**Générez la migration et mettez à jour la base de données :**

```bash
symfony console make:migration
symfony console doctrine:migrations:migrate
```

#### Étape 1.4 : Fixtures de données

Créez des fixtures pour peupler votre base avec des données de test :

```bash
composer require --dev orm-fixtures fakerphp/faker
```

Créez au minimum :

- 5 catégories de formation (Développement web, Data Science, Design, Marketing, Gestion de projet)
- 10 utilisateurs (5 formateurs et 5 apprenants)
- 15 formations variées avec différentes modalités et niveaux
- 25 inscriptions avec différents statuts

#### Étape 1.5 : Contrôleur `CategorieController` avec CRUD complet

Créez le contrôleur pour gérer les catégories :

```bash
symfony console make:crud Categorie
```

#### Étape 1.6 : Mission autonome - Contrôleur `UserController`

**Mission :** Sur le modèle du `CategorieController`, créez un `UserController` complet avec :

1. **Route `/user`** - Index listant tous les utilisateurs
2. **Route `/user/create`** - Création automatique d'un utilisateur avec Faker
3. **Route `/user/{id}`** - Affichage des détails d'un utilisateur
4. **Route `/user/{id}/update`** - Mise à jour aléatoire (email, téléphone, bio)
5. **Route `/user/{id}/delete`** - Suppression d'un utilisateur

### 📝 Phase 2 : Formulaires de Base (90 minutes)

#### Étape 2.1 : Mission autonome - Formulaire User

**Mission :** Sur le modèle du formulaire Categorie, créez un formulaire complet pour les utilisateurs

Créez les éléments suivants :

1. **FormType** : `UserType` avec tous les champs (email, prenom, nom, telephone, dateNaissance, entreprise, poste, bio)
2. **Contrôleur** : Modifiez `UserController` pour ajouter les actions `new`, `edit`, `delete` utilisant le formulaire
3. **Templates** : Créez `user/new.html.twig`, `user/edit.html.twig` et mettez à jour `user/index.html.twig` et `user/show.html.twig`

**Points d'attention :**

- Pour les champs : utilisez `EmailType`, `TextType`, `TelType`, `DateType`, `TextareaType`
- N'incluez pas le champ `password` dans ce formulaire (on le traitera séparément avec un formulaire d'inscription)
- N'incluez pas les champs `roles`, `photoProfil`, `dateCreation`, `estActif` (gérés automatiquement)
- Ajoutez des labels et placeholders appropriés

### ✅ Phase 3 : Validation des Données (75 minutes)

#### Étape 3.1 : Contraintes de validation sur Categorie

**Mission :** Ajoutez des contraintes de validation dans l'entité Categorie

Modifiez `src/Entity/Categorie.php` en ajoutant les imports et contraintes :

- `nom` : NotBlank, Length (min: 3, max: 100)
- `slug` : NotBlank, Regex (`/^[a-z0-9]+(?:-[a-z0-9]+)*$/`), UniqueEntity
- `description` : Length (max: 1000) - optionnel
- `couleur` : NotBlank, Regex (`/^#[0-9A-Fa-f]{6}$/`)
- `icone` : NotBlank, Regex (`/^fa-[\w-]+$/`)

Testez votre formulaire en essayant de soumettre des données invalides :

- Slug avec majuscules ou espaces
- Couleur mal formatée
- Nom trop court
- Icône invalide

#### Étape 3.2 : Contraintes de validation sur User

**Mission :** Ajoutez les contraintes de validation sur l'entité User

- `email` : NotBlank, Email, UniqueEntity
- `prenom` : NotBlank, Length (min: 2, max: 100)
- `nom` : NotBlank, Length (min: 2, max: 100)
- `telephone` : Regex (`/^[0-9\-\+\s\(\)\.]+$/`) - optionnel
- `dateNaissance` : LessThan ('today'), GreaterThan ('-120 years') - optionnel
- `entreprise` : Length (max: 255) - optionnel
- `poste` : Length (max: 100) - optionnel
- `bio` : Length (max: 1000) - optionnel

#### Étape 3.3 : Contrainte personnalisée - Validation de dates de formation

**Mission :** Créez une contrainte personnalisée pour valider les dates de formation

Créez le fichier `src/Validator/FormationDatesValides.php` (l'attribut de contrainte) :

```php
<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class FormationDatesValides extends Constraint
{
    public string $messageFinAvantDebut = 'La date de fin doit être postérieure à la date de début';
    public string $messageDebutDansLePasse = 'La date de début ne peut pas être dans le passé';
    public string $messageDureeInconsistante = 'La durée de la formation semble incohérente avec les dates fournies';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
```

Créez le fichier `src/Validator/FormationDatesValidesValidator.php` (le validateur) :

```php
<?php

namespace App\Validator;

use App\Entity\Formation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class FormationDatesValidesValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof FormationDatesValides) {
            throw new UnexpectedTypeException($constraint, FormationDatesValides::class);
        }

        if (!$value instanceof Formation) {
            return;
        }

        $dateDebut = $value->getDateDebut();
        $dateFin = $value->getDateFin();
        $duree = $value->getDuree();

        if (!$dateDebut || !$dateFin) {
            return; // Les champs NotBlank se chargeront de ces validations
        }

        // Vérifier que la date de fin est après la date de début
        if ($dateFin <= $dateDebut) {
            $this->context->buildViolation($constraint->messageFinAvantDebut)
                ->atPath('dateFin')
                ->addViolation();
        }

        // Vérifier que la date de début n'est pas dans le passé
        $aujourdhui = new \DateTime('today');
        if ($dateDebut < $aujourdhui) {
            $this->context->buildViolation($constraint->messageDebutDansLePasse)
                ->atPath('dateDebut')
                ->addViolation();
        }

        // Vérifier la cohérence de la durée (avec une tolérance)
        if ($duree) {
            // Calcul du nombre de jours entre les dates
            $interval = $dateDebut->diff($dateFin);
            $joursFormation = $interval->days;
            
            // On suppose une formation de 7h par jour en moyenne
            $heuresEstimees = $joursFormation * 7;
            
            // Tolérance de 50% (la durée réelle peut varier selon l'intensité)
            $toleranceBasse = $heuresEstimees * 0.5;
            $toleranceHaute = $heuresEstimees * 1.5;
            
            if ($duree < $toleranceBasse || $duree > $toleranceHaute) {
                $this->context->buildViolation($constraint->messageDureeInconsistante)
                    ->atPath('duree')
                    ->addViolation();
            }
        }
    }
}
```

#### Étape 3.4 : Application de la contrainte personnalisée

**Mission :** Ajoutez les contraintes sur l'entité Formation

- `titre` : NotBlank, Length (min: 10, max: 250)
- `slug` : NotBlank, Regex (`/^[a-z0-9]+(?:-[a-z0-9]+)*$/`)
- `description` : NotBlank, Length (min: 50)
- `programme` : NotBlank, Length (min: 50)
- `objectifs` : (pas de contraintes) - optionnel
- `prerequis` : (pas de contraintes) - optionnel
- `duree` : NotBlank, Positive, Range (min: 1, max: 1000) - en heures
- `niveau` : NotBlank, Choice (debutant, intermediaire, avance)
- `prix` : NotBlank, PositiveOrZero, Range (max: 10000)
- `capaciteMax` : NotBlank, Positive, Range (min: 1, max: 100)
- `modalite` : NotBlank, Choice (presentiel, distanciel, hybride)
- `dateDebut` : NotBlank
- `dateFin` : NotBlank
- `categorie` : NotNull (relation)
- `formateur` : NotNull (relation)
- **Contrainte de classe** : FormationDatesValides (personnalisée)

#### Étape 3.5 : Mission autonome - Formulaire Formation

**Mission :** Créez un formulaire complet pour créer et modifier une formation

Créez les éléments suivants :

1. **FormType** : `FormationType` avec tous les champs
2. **Contrôleur** : `FormationController` avec actions CRUD complètes (index, new, show, edit, delete)
3. **Templates** : Tous les templates nécessaires

**Points d'attention particuliers :**

- Utilisez `EntityType` pour les relations (categorie, formateur)
- Utilisez `DateType` pour les dates avec widget approprié
- Utilisez `MoneyType` pour le prix
- Utilisez `ChoiceType` pour niveau et modalite
- Utilisez `IntegerType` pour duree et capaciteMax
- Utilisez `CheckboxType` pour estPublie
- Testez que la contrainte personnalisée `FormationDatesValides` fonctionne bien

**Cas de test pour la contrainte personnalisée :**

- Essayez de créer une formation avec dateFin avant dateDebut
- Essayez de créer une formation avec dateDebut dans le passé
- Essayez de créer une formation où la durée est incohérente avec les dates

### 🎨 Phase 4 : Amélioration de l'Affichage des Formulaires (45 minutes)

#### Étape 4.1 : Personnalisation de l'affichage des erreurs

**Mission :** Créez un template personnalisé pour l'affichage des erreurs

Créez le fichier `templates/form/custom_errors.html.twig` :

```twig
{% block form_errors %}
    {% if errors|length > 0 %}
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-triangle"></i> Erreur(s) de validation :</strong>
            <ul class="mb-0 mt-2">
                {% for error in errors %}
                    <li>{{ error.message }}</li>
                {% endfor %}
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    {% endif %}
{% endblock %}
```

Pour utiliser ce template personnalisé, dans vos templates de formulaires :

```twig
{% form_theme form 'form/custom_errors.html.twig' %}
```

## 📖 Ressources utiles

### Documentation officielle

- [Formulaires Symfony](https://symfony.com/doc/current/forms.html)
- [Validation](https://symfony.com/doc/current/validation.html)
- [Contraintes de validation](https://symfony.com/doc/current/reference/constraints.html)
- [Contraintes personnalisées](https://symfony.com/doc/current/validation/custom_constraint.html)
- [Types de champs](https://symfony.com/doc/current/reference/forms/types.html)

### Commandes essentielles

```bash
# Création de FormType
symfony console make:form

# Création de contrainte de validation personnalisée
symfony console make:validator

# Création d'entité
symfony console make:entity

# Création de contrôleur
symfony console make:controller

# Migration
symfony console make:migration
symfony console doctrine:migrations:migrate

# Chargement des fixtures
symfony console doctrine:fixtures:load
```

### Types de champs fréquemment utilisés

- `TextType` : Champ texte simple
- `EmailType` : Champ email avec validation HTML5
- `TelType` : Champ téléphone
- `IntegerType` : Nombre entier
- `MoneyType` : Montant monétaire
- `DateType` : Sélection de date
- `TextareaType` : Zone de texte multi-lignes
- `ChoiceType` : Liste déroulante ou boutons radio
- `EntityType` : Sélection d'une entité Doctrine
- `CheckboxType` : Case à cocher
- `ColorType` : Sélecteur de couleur

### Contraintes de validation courantes

- `@Assert\NotBlank` : Champ non vide
- `@Assert\Length` : Longueur min/max
- `@Assert\Email` : Format email
- `@Assert\Regex` : Expression régulière
- `@Assert\Range` : Valeur dans un intervalle
- `@Assert\Positive` / `@Assert\PositiveOrZero` : Nombre positif
- `@Assert\Choice` : Valeur parmi une liste
- `@Assert\LessThan` / `@Assert\GreaterThan` : Comparaison
- `@UniqueEntity` : Unicité en base de données
