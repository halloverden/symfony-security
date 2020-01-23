<?php


namespace HalloVerden\Security\Interfaces;

use HalloVerden\Security\AccessDefinitions\AccessDefinedProperty;

/**
 * Interface IAccessDefinition
 * @package HalloVerden\Security\AccessDefinitions\Interfaces
 */
interface AccessDefinitionInterface {
  public function canCreate(): bool;
  public function canRead(): bool;
  public function canUpdate(): bool;
  public function canDelete(): bool;
  public function canReadProperty(string $propertyName): bool;
  public function canWriteProperty(string $propertyName): bool;
  public function filterData(AccessDefinedProperty $accessDefinitionValueType): array;
  public function setData(array $data, bool $force = false): self;
}
