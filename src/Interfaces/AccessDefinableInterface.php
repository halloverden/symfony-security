<?php


namespace HalloVerden\Security\Interfaces;


use HalloVerden\Security\AccessDefinitions\AccessDefinedProperty;

/**
 * Interface AccessDefinableInterface
 *
 * @package HalloVerden\Security\Interfaces
 */
interface AccessDefinableInterface {

  /**
   * @return array<string, AccessDefinedProperty>
   */
  public function getAccessDefinedProperties(): array;

  /**
   * @param string $property
   * @param array  $data
   */
  public function setAccessDefinedProperty(string $property, array $data): void;

}
