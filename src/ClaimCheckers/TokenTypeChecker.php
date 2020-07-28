<?php


namespace HalloVerden\Security\ClaimCheckers;


use Jose\Component\Checker\ClaimChecker;
use Jose\Component\Checker\InvalidClaimException;

class TokenTypeChecker implements ClaimChecker {
  private const CLAIM_NAME = 'type';

  /**
   * @var string[]
   */
  private $types;

  /**
   * TokenTypeChecker constructor.
   *
   * @param string[] $types
   */
  public function __construct(array $types) {
    $this->types = $types;
  }

  /**
   * @inheritDoc
   */
  public function checkClaim($value): void {
    if (!is_string($value)) {
      throw new InvalidClaimException("Invalid type", self::CLAIM_NAME, $value);
    }

    if (!in_array($value, $this->types)) {
      throw new InvalidClaimException("Invalid type", self::CLAIM_NAME, $value);
    }
  }

  /**
   * @inheritDoc
   */
  public function supportedClaim(): string {
    return self::CLAIM_NAME;
  }
}
