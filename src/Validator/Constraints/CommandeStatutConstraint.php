<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CommandeStatutConstraint extends Constraint
{
    public $message = 'Le statut "{{ value }}" n\'est pas valide.';
}
