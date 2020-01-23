<?php


namespace HalloVerden\Security\Interfaces;


interface AccessDefinableInterface {
  public function getAccessDefinedProperties(): array;
  public function setAccessDefinedProperties(array $properties);
}
