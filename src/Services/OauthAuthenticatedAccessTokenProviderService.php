<?php


namespace HalloVerden\Security\Services;


use HalloVerden\Contracts\Oidc\Tokens\OidcAccessTokenInterface;
use HalloVerden\Security\AccessTokenAuthenticator;
use HalloVerden\Security\Interfaces\OauthAuthenticatedAccessTokenProviderServiceInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class OauthAuthenticatedAccessTokenProviderService
 *
 * @package HalloVerden\Security\Services
 */
class OauthAuthenticatedAccessTokenProviderService implements OauthAuthenticatedAccessTokenProviderServiceInterface {

  /**
   * @var TokenStorageInterface
   */
  private $tokenStorage;

  /**
   * OauthAuthenticatedAccessTokenProviderService constructor.
   *
   * @param TokenStorageInterface $tokenStorage
   */
  public function __construct(TokenStorageInterface $tokenStorage) {
    $this->tokenStorage = $tokenStorage;
  }

  /**
   * @inheritDoc
   */
  public function getAuthenticatedAccessToken(): ?OidcAccessTokenInterface {
    $token = $this->tokenStorage->getToken();

    if (null === $token || !$token->isAuthenticated() || !$token->hasAttribute(AccessTokenAuthenticator::ATTRIBUTE_OAUTH_TOKEN)) {
      return null;
    }

    $accessToken = $token->getAttribute(AccessTokenAuthenticator::ATTRIBUTE_OAUTH_TOKEN);

    if (!$accessToken instanceof OidcAccessTokenInterface) {
      throw new \LogicException(\sprintf("Token must implement %s", OidcAccessTokenInterface::class));
    }

    return $accessToken;
  }

}
