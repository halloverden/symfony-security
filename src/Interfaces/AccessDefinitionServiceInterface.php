<?php


namespace HalloVerden\Security\Interfaces;

interface AccessDefinitionServiceInterface {

  /**
   * @param AccessDefinableInterface $accessDefinable
   */
  public function handleAccessDefinable(AccessDefinableInterface $accessDefinable): void;
}
