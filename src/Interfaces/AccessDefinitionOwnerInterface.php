<?php


namespace HalloVerden\Security\Interfaces;

/**
 * Interface AccessDefinitionOwnerInterface
 *
 * @package HalloVerden\Security\Interfaces
 */
interface AccessDefinitionOwnerInterface {

  /**
   * @param AccessDefinitionOwnerInterface $owner
   *
   * @return bool
   */
  public function equalAccessDefinitionOwner(AccessDefinitionOwnerInterface $owner): bool;

}
