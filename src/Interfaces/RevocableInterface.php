<?php


namespace HalloVerden\Security\Interfaces;


interface RevocableInterface {
  const REVOKED_AT_PROPERTY = 'revokedAt';

  /**
   * @return bool
   */
  public function isRevoked(): bool;

  /**
   * @return \DateTime|null
   */
  public function getRevokedAt(): ?\DateTime;

  /**
   * @param \DateTime $revokedAt
   *
   * @return mixed
   */
  public function setRevokedAt(\DateTime $revokedAt);

  /**
   * @return string
   */
  public function getRevokedAtProperty(): string;

}
