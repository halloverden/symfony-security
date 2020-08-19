<?php


namespace HalloVerden\Security\AccessDefinitions\Metadata;


use Metadata\PropertyMetadata;

/**
 * Class AccessDefinitionPropertyMetadata
 *
 * @package HalloVerden\Security\AccessDefinitions\Metadata
 */
class AccessDefinitionPropertyMetadata extends PropertyMetadata {

  /**
   * @var array|null
   */
  public $readRoles;

  /**
   * @var array|null
   */
  public $writeRoles;

  /**
   * @var string|null
   */
  public $readMethod;

  /**
   * @var string|null
   */
  public $writeMethod;

  /**
   * @var array|null
   */
  public $readScopes;

  /**
   * @var array|null
   */
  public $writeScopes;

  /**
   * @param array $data
   *
   * @return $this
   */
  public function setPropertyMetadataFromConfigData(array $data): self {
    $this->readRoles = $data['canRead']['roles'] ?? null;
    $this->readScopes = $data['canRead']['scopes'] ?? null;
    $this->readMethod = $data['canRead']['method'] ?? null;

    $this->writeRoles = $data['canWrite']['roles'] ?? null;
    $this->writeScopes = $data['canWrite']['scopes'] ?? null;
    $this->writeMethod = $data['canWrite']['method'] ?? null;

    return $this;
  }

  /**
   * @return string
   */
  public function serialize() {
    return serialize([
      $this->readRoles,
      $this->writeRoles,
      $this->readMethod,
      $this->writeMethod,
      $this->readScopes,
      $this->writeScopes,
      parent::serialize()
    ]);
  }

  /**
   * @param string $str
   */
  public function unserialize($str) {
    $unserialized = unserialize($str);
    [
      $this->readRoles,
      $this->writeRoles,
      $this->readMethod,
      $this->writeMethod,
      $this->readScopes,
      $this->writeScopes,
      $parentStr
    ] = $unserialized;

    parent::unserialize($parentStr);
  }

}
