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
    foreach ($data as $access => $value) {
      foreach ($value as $type => $v) {
        if (\property_exists($this, $property = $access . \ucfirst($type))) {
          $this->$property = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data[$access][$type]);
        }
      }
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
