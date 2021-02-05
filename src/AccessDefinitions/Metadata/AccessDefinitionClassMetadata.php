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
   * @var AccessDefinitionMetadata|null
   */
  public $canCreateEveryone;

  /**
   * @var AccessDefinitionMetadata|null
   */
  public $canReadEveryone;

  /**
   * @var AccessDefinitionMetadata|null
   */
  public $canUpdateEveryone;

  /**
   * @var AccessDefinitionMetadata|null
   */
  public $canDeleteEveryone;

  /**
   * @var AccessDefinitionMetadata|null
   */
  public $canCreateOwner;

  /**
   * @var AccessDefinitionMetadata|null
   */
  public $canReadOwner;

  /**
   * @var AccessDefinitionMetadata|null
   */
  public $canUpdateOwner;

  /**
   * @var AccessDefinitionMetadata|null
   */
  public $canDeleteOwner;

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
    if (isset($data['canCreate']['everyone'])) {
      $this->canCreateEveryone = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canCreate']['everyone']);
    } elseif (isset($data['canCreate'])) {
      $this->canCreateEveryone = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canCreate']);
    }

    if (isset($data['canRead']['everyone'])) {
      $this->canReadEveryone = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canRead']['everyone']);
    } elseif (isset($data['canRead'])) {
      $this->canReadEveryone = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canRead']);
    }

    if (isset($data['canUpdate']['everyone'])) {
      $this->canUpdateEveryone = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canUpdate']['everyone']);
    } elseif (isset($data['canUpdate'])) {
      $this->canUpdateEveryone = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canUpdate']);
    }

    if (isset($data['canDelete']['everyone'])) {
      $this->canDeleteEveryone = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canDelete']['everyone']);
    } elseif (isset($data['canDelete'])) {
      $this->canDeleteEveryone = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canDelete']);
    }

    if (isset($data['canCreate']['owner'])) {
      $this->canCreateOwner = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canCreate']['owner']);
    }

    if (isset($data['canRead']['owner'])) {
      $this->canReadOwner = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canRead']['owner']);
    }

    if (isset($data['canUpdate']['owner'])) {
      $this->canUpdateOwner = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canUpdate']['owner']);
    }

    if (isset($data['canDelete']['owner'])) {
      $this->canDeleteOwner = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canDelete']['owner']);
    }

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function serialize() {
    return serialize([
      $this->canCreateEveryone,
      $this->canReadEveryone,
      $this->canUpdateEveryone,
      $this->canDeleteEveryone,
      $this->canCreateOwner,
      $this->canReadOwner,
      $this->canUpdateOwner,
      $this->canDeleteOwner,
      parent::serialize()
    ]);
  }

  /**
   * @inheritDoc
   */
  public function unserialize($str) {
    $unserialized = unserialize($str);
    [
      $this->canCreateEveryone,
      $this->canReadEveryone,
      $this->canUpdateEveryone,
      $this->canDeleteEveryone,
      $this->canCreateOwner,
      $this->canReadOwner,
      $this->canUpdateOwner,
      $this->canDeleteOwner,
      $parentStr
    ] = $unserialized;

    parent::unserialize($parentStr);
  }

}
