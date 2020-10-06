<?php


namespace HalloVerden\Security\Interfaces;

use HalloVerden\Security\AccessDefinitions\AccessDefinedProperty;

/**
 * Interface AccessDefinitionFilterServiceInterface
 *
 * @package HalloVerden\Security\Interfaces
 */
interface AccessDefinitionFilterServiceInterface {

  /**
   * @param AccessDefinableInterface $accessDefinable
   */
  public function filterAccessDefinable(AccessDefinableInterface $accessDefinable): void;

  /**
   * @param AccessDefinedProperty $accessDefinedProperty
   *
   * @return array
   */
  public function filterAccessDefinedProperty(AccessDefinedProperty $accessDefinedProperty): array;

}
