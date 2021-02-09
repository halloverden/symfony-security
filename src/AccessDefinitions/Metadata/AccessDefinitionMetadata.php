<?php


namespace HalloVerden\Security\AccessDefinitions\Metadata;

/**
 * Class AccessDefinitionMetadata
 *
 * @package HalloVerden\Security\AccessDefinitions\Metadata
 */
class AccessDefinitionMetadata {

  /**
   * @var string[]
   */
  public $roles;

  /**
   * @var string[]
   */
  public $scopes;

  /**
   * @var string|null
   */
  public $method;

  /**
   * @param array $data
   *
   * @return AccessDefinitionMetadata
   */
  public function setMetadataFromConfigData(array $data): self {
    foreach ($data as $property => $value) {
      if (\property_exists($this, $property)) {
        $this->$property = $value;
      }
    }

    return $this;
  }

}
