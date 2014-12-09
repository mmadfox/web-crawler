<?php
namespace Madfox\WebCrawler\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VisitValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (isset($constraint->getHistory()[$value])) {
            return true;
        }

        return false;
    }
}