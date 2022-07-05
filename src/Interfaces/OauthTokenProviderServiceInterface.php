<?php


namespace HalloVerden\Security\Interfaces;


use HalloVerden\Contracts\Oidc\Tokens\OidcAccessTokenInterface;

interface OauthTokenProviderServiceInterface {

  /**
   * @param array  $claims
   * @param string $rawToken
   *
   * @return OidcAccessTokenInterface|null
   */
  public function getOauthTokenFromJWT(array $claims, string $rawToken): ?OidcAccessTokenInterface;

}
