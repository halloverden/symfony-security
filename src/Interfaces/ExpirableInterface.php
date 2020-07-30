<?php


namespace HalloVerden\Security\Interfaces;


interface ExpirableInterface {

  /**
   * @return bool
   */
  public function isExpired(): bool;

}
