<?php


namespace HalloVerden\Security\Traits;


use HalloVerden\Security\AccessDefinitions\Metadata\AccessDefinitionMetadata;


/**
 * Trait SetAccessDefinitionMetadataTrait
 *
 * @package HalloVerden\Security\Traits
 */
trait SetAccessDefinitionMetadataTrait {

  /**
   * @param array $data
   *
   * @return $this
   */
  public function setMetadataFromConfigData(array $data): self {
    foreach ($data as $access => $value) {
      foreach ($value as $type => $v) {
        if (\property_exists($this, $property = $access . \ucfirst($type))) {
          $this->$property = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data[$access][$type]);
        }
      }
    }

    return $this;
  }

}
