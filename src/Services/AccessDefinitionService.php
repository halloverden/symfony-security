<?php

namespace HalloVerden\Security\Services;

use HalloVerden\Security\AccessDefinitions\AccessDefinedProperty;
use HalloVerden\Security\Interfaces\AccessDefinableInterface;
use HalloVerden\Security\Interfaces\AccessDefinitionInterface;
use HalloVerden\Security\Interfaces\AccessDefinitionServiceInterface;

class AccessDefinitionService implements AccessDefinitionServiceInterface {

  /**
   * @var iterable
   */
  private $accessDefinitions;

  /**
   * AccessDefinitionService constructor.
   *
   * @param iterable $accessDefinitions
   */
  public function __construct(iterable $accessDefinitions) {
    $this->accessDefinitions = $accessDefinitions;
  }

  /**
   * @inheritDoc
   */
  public function handleAccessDefinable(AccessDefinableInterface $accessDefinable): void {
    $properties = $accessDefinable->getAccessDefinedProperties();
    $data = [];

    foreach ($properties as $key => $accessDefinitionValueType) {
      /* @var $accessDefinitionValueType AccessDefinedProperty */
      if (!class_exists($accessDefinitionValueType->getAccessDefinitionClass()) || !$accessDefinition = $this->getAccessDefinition($accessDefinitionValueType->getAccessDefinitionClass())) {
        throw new \RuntimeException('AccessDefinition class ' . $accessDefinitionValueType->getAccessDefinitionClass() . ' not found.');
      }

      $data[$key] = $accessDefinition->filterData($accessDefinitionValueType);
    }

    $accessDefinable->setAccessDefinedProperties(
      $data
    );
  }

  /**
   * @param string $class
   * @return AccessDefinitionInterface|null
   */
  private function getAccessDefinition(string $class): ?AccessDefinitionInterface {
    foreach ($this->accessDefinitions as $accessDefinition) {
      if ($accessDefinition instanceof $class) {
        return $accessDefinition;
      }
    }

    return null;
  }
}
