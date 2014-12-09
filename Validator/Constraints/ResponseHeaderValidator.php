<?php
namespace Madfox\WebCrawler\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ResponseHeaderValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        return true;
    }
}
