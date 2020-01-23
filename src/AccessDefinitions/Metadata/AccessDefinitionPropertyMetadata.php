<?php


namespace HalloVerden\Security\AccessDefinitions\Metadata;


use Metadata\PropertyMetadata;

class AccessDefinitionPropertyMetadata extends PropertyMetadata {

  /**
   * @var array|null
   */
  public $readRoles;

  /**
   * @var array|null
   */
  public $writeRoles;

  /**
   * @var string|null
   */
  public $readMethod;

  /**
   * @var string|null
   */
  public $writeMethod;

  /**
   * @var bool
   */
  public $managed = false;

  /**
   * @return string
   */
  public function serialize() {
    return serialize([
      $this->readRoles,
      $this->writeRoles,
      $this->readMethod,
      $this->writeMethod,
      $this->managed,
      parent::serialize()
    ]);
  }

  /**
   * @param string $str
   */
  public function unserialize($str) {
    $unserialized = unserialize($str);
    [
      $this->readRoles,
      $this->writeRoles,
      $this->readMethod,
      $this->writeMethod,
      $this->managed,
      $parentStr
    ] = $unserialized;

    parent::unserialize($parentStr);
  }
}
