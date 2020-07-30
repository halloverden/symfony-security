<?php


namespace HalloVerden\Security\Interfaces;


use HalloVerden\Contracts\Oidc\Tokens\OidcAccessTokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface OauthUserProviderServiceInterface {

  /**
   * @param OidcAccessTokenInterface $accessToken
   *
   * @return UserInterface|null
   */
  public function getUserFromAccessToken(OidcAccessTokenInterface $accessToken): ?UserInterface;

}
