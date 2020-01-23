<?php


namespace HalloVerden\Security\AccessDefinitions\Metadata;


use Metadata\MergeableClassMetadata;

class AccessDefinitionClassMetadata extends MergeableClassMetadata {

  /**
   * @return AccessDefinitionPropertyMetadata
   */
  public function getPropertyMetadata(string $propertyName): ?AccessDefinitionPropertyMetadata {
    if (isset($this->propertyMetadata[$propertyName])) {
      $propertyMetadata = $this->propertyMetadata[$propertyName];
      if ($propertyMetadata instanceof AccessDefinitionPropertyMetadata) {
        return $propertyMetadata;
      }
    }

    return null;
  }

  /**
   * @param string $propertyName
   *
   * @return AccessDefinitionPropertyMetadata|null
   */
  public function getManagedPropertyMetadata(string $propertyName): ?AccessDefinitionPropertyMetadata {
    $propertyMetadata = $this->getPropertyMetadata($propertyName);

    if (!$propertyMetadata || !$propertyMetadata->managed) {
      return null;
    }

    return $propertyMetadata;
  }

}
