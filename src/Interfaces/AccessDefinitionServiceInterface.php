<?php


namespace HalloVerden\Security\Interfaces;

interface AccessDefinitionServiceInterface {
  public function canCreate(string $class): bool;
  public function canRead(string $class): bool;
  public function canUpdate(string $class): bool;
  public function canDelete(string $class): bool;
  public function canReadProperty(string $class, string $property): bool;
  public function canWriteProperty(string $class, string $property): bool;
}
