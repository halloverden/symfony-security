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
  const ERROR_NO_READ_ACCESS = 'a9ef0898-db41-4ecd-bd17-22534a5a35d2';
  const ERROR_NO_WRITE_ACCESS = '04a6f004-f1a1-43f5-9555-823961104aca';

  protected static $errorNames = [
    self::ERROR_NO_READ_ACCESS => 'ERROR_NO_READ_ACCESS',
    self::ERROR_NO_WRITE_ACCESS => 'ERROR_NO_WRITE_ACCESS',
  ];

  /**
   * @var string
   */
  public $noReadAccessMessage = 'No read access to property {{ property }}';

  /**
   * @var string
   */
  public $noWriteAccessMessage = 'No write access to property {{ property }}';

  /**
   * @var string|null
   */
  public $class;

  /**
   * @var string|null
   */
  public $property;

  /**
   * @var bool
   */
  public $read = false;

  /**
   * @var bool
   */
  public $write = true;
}
