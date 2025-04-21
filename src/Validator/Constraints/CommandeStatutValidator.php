<?php
// src/Validator/Constraints/CommandeStatutValidator.php
namespace App\Validator\Constraints;

use App\Entity\Commande;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CommandeStatutValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!in_array($value, [
            Commande::STATUT_EN_COURS,
            Commande::STATUT_LIVRE,
            Commande::STATUT_PAYE,
            Commande::STATUT_ANNULE,
        ])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}