<?php


namespace HalloVerden\Security\Services;


use HalloVerden\Contracts\Oidc\Tokens\OidcTokenInterface;
use HalloVerden\Security\ClaimCheckers\TokenTypeChecker;
use HalloVerden\Security\Interfaces\OauthAuthenticatorServiceInterface;
use HalloVerden\Security\Interfaces\OauthJwkSetProviderServiceInterface;
use Jose\Easy\JWT;
use Jose\Easy\Load;

/**
 * Class OauthAuthenticatorService
 *
 * @package HalloVerden\Security\Services
 */
class OauthAuthenticatorService implements OauthAuthenticatorServiceInterface {
  const DEFAULT_MANDATORY_CLAIMS = [
    'exp',
    'iss',
    'iat',
    'type',
  ];

  const DEFAULT_VALID_ACCESS_TOKEN_TYPES = [
    OidcTokenInterface::TYPE_ACCESS,
    OidcTokenInterface::TYPE_ACCESS_CLIENT_CREDENTIALS,
  ];

  /**
   * @var string
   */
  private $issuer;

  /**
   * @var OauthJwkSetProviderServiceInterface
   */
  private $oauthJwkSetProvider;

  /**
   * @var array
   */
  private $validAccessTokenTypes;

  /**
   * @var array
   */
  private $mandatoryClaims;

  /**
   * OauthAuthenticatorService constructor.
   *
   * @param string                              $issuer
   * @param OauthJwkSetProviderServiceInterface $oauthJwkSetProvider
   */
  public function __construct(string $issuer, OauthJwkSetProviderServiceInterface $oauthJwkSetProvider) {
    $this->issuer = $issuer;
    $this->oauthJwkSetProvider = $oauthJwkSetProvider;
    $this->validAccessTokenTypes = self::DEFAULT_VALID_ACCESS_TOKEN_TYPES;
    $this->mandatoryClaims = self::DEFAULT_MANDATORY_CLAIMS;
  }

  /**
   * @inheritDoc
   */
  public function validateAndGetAccessToken(string $token): JWT {
    return Load::jws($token)
      ->exp(100)
      ->iat(100)
      ->iss($this->issuer)
      ->keyset($this->oauthJwkSetProvider->getPublicKey($this->issuer))
      ->mandatory($this->getMandatoryClaims())
      ->claim('type', new TokenTypeChecker($this->getValidAccessTokenTypes()))
      ->run();
  }

  /**
   * @return array
   */
  public function getValidAccessTokenTypes(): array {
    return $this->validAccessTokenTypes ?: self::DEFAULT_VALID_ACCESS_TOKEN_TYPES;
  }

  /**
   * @param array $validAccessTokenTypes
   */
  public function setValidAccessTokenTypes(array $validAccessTokenTypes): void {
    $this->validAccessTokenTypes = $validAccessTokenTypes;
  }

  /**
   * @return array
   */
  public function getMandatoryClaims(): array {
    return $this->mandatoryClaims;
  }

  /**
   * @param array $mandatoryClaims
   */
  public function setMandatoryClaims(array $mandatoryClaims): void {
    $this->mandatoryClaims = $mandatoryClaims;
  }

}
