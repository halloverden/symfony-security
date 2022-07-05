<?php


namespace HalloVerden\Security\Services;


use HalloVerden\Contracts\Oidc\Tokens\OidcTokenInterface;
use HalloVerden\Security\ClaimCheckers\TokenTypeChecker;
use HalloVerden\Security\Interfaces\OauthAuthenticatorServiceInterface;
use HalloVerden\Security\Interfaces\OauthJwkSetProviderServiceInterface;
use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker\ExpirationTimeChecker;
use Jose\Component\Checker\IssuedAtChecker;
use Jose\Component\Checker\IssuerChecker;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\Util\JsonConverter;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\JWSLoader;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializerManager;

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
  public function validateAndGetAccessToken(string $token): array {
    $algorithmManager = new AlgorithmManager([new RS256()]);
    $jwsVerifier = new JWSVerifier($algorithmManager);
    $serializerManager = new JWSSerializerManager([new CompactSerializer()]);
    $jwsLoader = new JWSLoader($serializerManager, $jwsVerifier, null);


    $jws = $jwsLoader->loadAndVerifyWithKeySet($token, $this->oauthJwkSetProvider->getPublicKey($this->issuer), $signature);

    $claimCheckerManager = new ClaimCheckerManager([
      new IssuedAtChecker(100),
      new ExpirationTimeChecker(100),
      new IssuerChecker([$this->issuer]),
      new TokenTypeChecker($this->getValidAccessTokenTypes())
    ]);

    $claims = JsonConverter::decode($jws->getPayload());
    $claimCheckerManager->check($claims);

    return $claims;
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
