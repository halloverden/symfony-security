<?php


namespace HalloVerden\Security\AccessDefinitions\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class HasAccess
 * @package HalloVerden\Security\AccessDefinitions\Constraints
 *
 * @Annotation()
 */
class HasAccess extends Constraint {
  /**
   * @var string
   */
  public $class;

  /**
   * @var string
   */
  public $property;

  /**
   * @var string
   */
  public $propertyPath;

  /**
   * @var bool
   */
  public $read = false;

  /**
   * @var bool
   */
  public $write = true;
}
