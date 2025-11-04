<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
final class FormationDatesValides extends Constraint
{
    public string $messageFinAvantDebut = 'La date de fin doit être postérieure à la date de début';
    public string $messageDebutDansLePasse = 'La date de début ne peut pas être dans le passé';
    public string $messageDureeInconsistante = 'La durée de la formation semble incohérente avec les dates fournies';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
