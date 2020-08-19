<?php


namespace HalloVerden\Security\Interfaces;

/**
 * Interface AccessDefinitionServiceInterface
 *
 * @package HalloVerden\Security\Interfaces
 */
interface AccessDefinitionServiceInterface {

  /**
   * @param string $class
   *
   * @return bool
   */
  public function canCreate(string $class): bool;

  /**
   * @param string $class
   *
   * @return bool
   */
  public function canRead(string $class): bool;

  /**
   * @param string $class
   *
   * @return bool
   */
  public function canUpdate(string $class): bool;

  /**
   * @param string $class
   *
   * @return bool
   */
  public function canDelete(string $class): bool;

  /**
   * @param string $class
   * @param string $property
   *
   * @return bool
   */
  public function canReadProperty(string $class, string $property): bool;

  /**
   * @param string $class
   * @param string $property
   *
   * @return bool
   */
  public function canWriteProperty(string $class, string $property): bool;

}
