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
   * @var AccessDefinitionMetadata|null
   */
  public $canReadEveryone;

  /**
   * @var AccessDefinitionMetadata|null
   */
  public $canWriteEveryone;

  /**
   * @var AccessDefinitionMetadata|null
   */
  public $canReadOwner;

  /**
   * @var AccessDefinitionMetadata|null
   */
  public $canWriteOwner;

  /**
   * @param array $data
   *
   * @return $this
   */
  public function setPropertyMetadataFromConfigData(array $data): self {
    if (isset($data['canRead']['everyone'])) {
      $this->canReadEveryone = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canRead']['everyone']);
    } elseif ($data['canRead']) {
      $this->canReadEveryone = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canRead']);
    }

    if (isset($data['canWrite']['everyone'])) {
      $this->canWriteEveryone = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canWrite']['everyone']);
    } elseif (isset($data['canWrite'])) {
      $this->canWriteEveryone = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canWrite']);
    }

    if (isset($data['canRead']['owner'])) {
      $this->canReadOwner = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canRead']['owner']);
    }

    if (isset($data['canWrite']['owner'])) {
      $this->canWriteOwner = (new AccessDefinitionMetadata())->setMetadataFromConfigData($data['canWrite']['owner']);
    }

    return $this;
  }

  /**
   * @return string
   */
  public function serialize() {
    return serialize([
      $this->canReadEveryone,
      $this->canReadOwner,
      $this->canWriteEveryone,
      $this->canWriteOwner,
      parent::serialize()
    ]);
  }

  /**
   * @param string $str
   */
  public function unserialize($str) {
    $unserialized = unserialize($str);
    [
      $this->canReadEveryone,
      $this->canReadOwner,
      $this->canWriteEveryone,
      $this->canWriteOwner,
      $parentStr
    ] = $unserialized;

    parent::unserialize($parentStr);
  }

}
