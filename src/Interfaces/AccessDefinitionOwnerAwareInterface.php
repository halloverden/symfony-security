<?php


namespace HalloVerden\Security\Interfaces;

/**
 * Interface AccessDefinitionOwnerAwareInterface
 *
 * @package HalloVerden\Security\Interfaces
 */
interface AccessDefinitionOwnerAwareInterface {

  /**
   * @return AccessDefinitionOwnerInterface
   */
  public function getAccessDefinitionOwner(): ?AccessDefinitionOwnerInterface;

}
