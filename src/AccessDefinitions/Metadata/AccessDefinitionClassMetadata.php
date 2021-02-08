<?php


namespace HalloVerden\Security\AccessDefinitions\Metadata;


use HalloVerden\Security\Traits\SetAccessDefinitionMetadataTrait;
use Metadata\MergeableClassMetadata;

/**
 * Class AccessDefinitionClassMetadata
 *
 * @package HalloVerden\Security\AccessDefinitions\Metadata
 */
class AccessDefinitionClassMetadata extends MergeableClassMetadata {
  use SetAccessDefinitionMetadataTrait;

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
