<?php


namespace HalloVerden\Security\AccessDefinitions\Constraints;

use HalloVerden\Security\Interfaces\AccessDefinitionEntityInterface;
use HalloVerden\Security\Interfaces\AccessDefinitionInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class HasAccessValidator
 * @package HalloVerden\Security\AccessDefinitions\Constraints
 */
class HasAccessValidator extends ConstraintValidator {
  /**
   * @var iterable
   */
  private $accessDefinitions;

  /**
   * HasAccessValidator constructor.
   *
   * @param iterable $accessDefinitions
   */
  public function __construct(iterable $accessDefinitions) {
    $this->accessDefinitions = $accessDefinitions;
  }

  /**
   * @param mixed $value
   * @param Constraint $constraint
   */
  public function validate($value, Constraint $constraint) {
    if (!$constraint instanceof HasAccess) {
      throw new UnexpectedTypeException($constraint, HasAccess::class);
    }

    if (!class_exists($constraint->class)) {
      throw new \RuntimeException('AccessDefinition class ' . $constraint->class  . ' does not exist');
    }

    $context = $this->context;

    foreach ($this->accessDefinitions as $accessDefinition) {
      if ($accessDefinition instanceof $constraint->class) {
        if($context->getObject() instanceof AccessDefinitionEntityInterface) {
          $properties = $context->getObject()->getAccessDefinitionProperties();
          /* @var $accessDefinition AccessDefinitionInterface */
          $accessDefinition->setData($properties);
        }

        $property = $constraint->property;

        if ($constraint->read && !$accessDefinition->canReadProperty($property)) {
          $context->buildViolation($constraint->noReadAccessMessage)->setParameter('{{ property }}', $property)->setCode(HasAccess::ERROR_NO_READ_ACCESS)->addViolation();
        }

        if ($constraint->write && !$accessDefinition->canWriteProperty($property)) {
          $context->buildViolation($constraint->noWriteAccessMessage)->setParameter('{{ property }}', $property)->setCode(HasAccess::ERROR_NO_WRITE_ACCESS)->addViolation();
        }
      }
    }
  }
}
