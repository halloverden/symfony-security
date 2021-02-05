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
    $this->roles = $data['roles'] ?? null;
    $this->scopes = $data['scopes'] ?? null;
    $this->method = $data['method'] ?? null;

    return $this;
  }

}
