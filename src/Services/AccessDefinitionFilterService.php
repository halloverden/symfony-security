<?php


namespace HalloVerden\Security\Services;


use HalloVerden\Security\AccessDefinitions\AccessDefinedProperty;
use HalloVerden\Security\Interfaces\AccessDefinableInterface;
use HalloVerden\Security\Interfaces\AccessDefinitionFilterServiceInterface;
use HalloVerden\Security\Interfaces\AccessDefinitionServiceInterface;

/**
 * Class AccessDefinitionFilterService
 *
 * @package HalloVerden\Security\Services
 */
class AccessDefinitionFilterService implements AccessDefinitionFilterServiceInterface {

  /**
   * @var AccessDefinitionServiceInterface
   */
  private $accessDefinitionService;

  /**
   * AccessDefinitionFilterService constructor.
   *
   * @param AccessDefinitionServiceInterface $accessDefinitionService
   */
  public function __construct(AccessDefinitionServiceInterface $accessDefinitionService) {
    $this->accessDefinitionService = $accessDefinitionService;
  }

  /**
   * @inheritDoc
   */
  public function filterAccessDefinable(AccessDefinableInterface $accessDefinable): void {
    foreach ($accessDefinable->getAccessDefinedProperties() as $property => $accessDefinedProperty) {
      $accessDefinable->setAccessDefinedProperty($property, $this->filterAccessDefinedProperty($accessDefinedProperty));
    }
  }

  /**
   * @inheritDoc
   */
  public function filterAccessDefinedProperty(AccessDefinedProperty $accessDefinedProperty): array {
    $data = $accessDefinedProperty->getData();

    foreach (array_keys($data) as $property) {
      if ($accessDefinedProperty->isRead() && !$this->accessDefinitionService->canReadProperty($accessDefinedProperty->getClass(), $property)) {
        unset($data[$property]);
      }

      if ($accessDefinedProperty->isWrite() && !$this->accessDefinitionService->canWriteProperty($accessDefinedProperty->getClass(), $property)) {
        unset($data[$property]);
      }
    }

    return $data;
  }

}
