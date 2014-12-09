<?php
namespace Madfox\WebCrawler\Validator;

use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\DefaultTranslator;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Validator\RecursiveValidator;

class ValidatorFactory
{
    /**
     * @return RecursiveValidator
     */
    public function createValidator()
    {
        $contextFactory   = new ExecutionContextFactory(new DefaultTranslator());
        $validatorFactory = new ConstraintValidatorFactory();
        $metadataFactory  = new LazyLoadingMetadataFactory();

        return new RecursiveValidator($contextFactory, $metadataFactory, $validatorFactory,  []);
    }
}