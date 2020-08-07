<?php


namespace HalloVerden\Security\AccessDefinitions\Metadata;


use Metadata\MergeableClassMetadata;

/**
 * Class AccessDefinitionClassMetadata
 *
 * @package HalloVerden\Security\AccessDefinitions\Metadata
 */
class AccessDefinitionClassMetadata extends MergeableClassMetadata {

  /**
   * @var string[]|null
   */
  public $canCreateRoles;

  /**
   * @var string[]|null
   */
  public $canCreateScopes;

  /**
   * @var string|null
   */
  public $canCreateMethod;

  /**
   * @var string[]|null
   */
  public $canReadRoles;

  /**
   * @var string[]|null
   */
  public $canReadScopes;

  /**
   * @var string|null
   */
  public $canReadMethod;

  /**
   * @var string[]|null
   */
  public $canUpdateRoles;

  /**
   * @var string[]|null
   */
  public $canUpdateScopes;

  /**
   * @var string|null
   */
  public $canUpdateMethod;

  /**
   * @var string[]|null
   */
  public $canDeleteRoles;

  /**
   * @var string[]|null
   */
  public $canDeleteScopes;

  /**
   * @var string|null
   */
  public $canDeleteMethod;

  /**
   * @param string $propertyName
   *
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
   * @param array $data
   *
   * @return $this
   */
  public function setClassMetadataFromConfigData(array $data): self {
    $this->canCreateRoles = $data['canCreate']['roles'] ?? null;
    $this->canCreateScopes = $data['canCreate']['scopes'] ?? null;
    $this->canCreateScopes = $data['canCreate']['method'] ?? null;

    $this->canReadRoles = $data['canRead']['roles'] ?? null;
    $this->canReadScopes = $data['canRead']['scopes'] ?? null;
    $this->canReadMethod = $data['canRead']['method'] ?? null;

    $this->canUpdateRoles = $data['canUpdate']['roles'] ?? null;
    $this->canUpdateScopes = $data['canUpdate']['scopes'] ?? null;
    $this->canUpdateMethod = $data['canUpdate']['method'] ?? null;

    $this->canDeleteRoles = $data['canDelete']['roles'] ?? null;
    $this->canDeleteScopes = $data['canDelete']['scopes'] ?? null;
    $this->canDeleteMethod = $data['canDelete']['method'] ?? null;

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function serialize() {
    return serialize([
      $this->canCreateRoles,
      $this->canCreateScopes,
      $this->canCreateMethod,
      $this->canReadRoles,
      $this->canReadScopes,
      $this->canReadMethod,
      $this->canUpdateRoles,
      $this->canUpdateScopes,
      $this->canUpdateMethod,
      $this->canDeleteRoles,
      $this->canDeleteScopes,
      $this->canDeleteMethod,
      parent::serialize()
    ]);
  }

  /**
   * @inheritDoc
   */
  public function unserialize($str) {
    $unserialized = unserialize($str);
    [
      $this->canCreateRoles,
      $this->canCreateScopes,
      $this->canCreateMethod,
      $this->canReadRoles,
      $this->canReadScopes,
      $this->canReadMethod,
      $this->canUpdateRoles,
      $this->canUpdateScopes,
      $this->canUpdateMethod,
      $this->canDeleteRoles,
      $this->canDeleteScopes,
      $this->canDeleteMethod,
      $parentStr
    ] = $unserialized;

    parent::unserialize($parentStr);
  }

}
