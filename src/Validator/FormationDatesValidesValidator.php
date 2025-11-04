<?php

namespace App\Validator;

use App\Entity\Formation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class FormationDatesValidesValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
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
