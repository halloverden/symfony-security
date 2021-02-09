<?php


namespace HalloVerden\Security\AccessDefinitions\Metadata;


use HalloVerden\Security\Traits\SetAccessDefinitionMetadataTrait;
use Metadata\PropertyMetadata;

/**
 * Class AccessDefinitionPropertyMetadata
 *
 * @package HalloVerden\Security\AccessDefinitions\Metadata
 */
class AccessDefinitionPropertyMetadata extends PropertyMetadata {
  use SetAccessDefinitionMetadataTrait;

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
