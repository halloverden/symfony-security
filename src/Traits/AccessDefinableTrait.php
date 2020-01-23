<?php


namespace HalloVerden\Security\Traits;

/**
 * Trait TAccessDefinable
 * @package HalloVerden\Security\AccessDefinitions\Traits
 */
trait AccessDefinableTrait {
  /**
   * @param array $properties
   */
  public function setAccessDefinedProperties(array $properties) {
    foreach ($properties as $property => $value) {
      $this->{$property} = $value;
    }
  }
}
