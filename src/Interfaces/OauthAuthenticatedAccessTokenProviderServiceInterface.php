<?php


namespace HalloVerden\Security\Interfaces;


use HalloVerden\Contracts\Oidc\Tokens\OidcAccessTokenInterface;

interface OauthAuthenticatedAccessTokenProviderServiceInterface {

  /**
   * @return OidcAccessTokenInterface|null
   */
  public function getAuthenticatedAccessToken(): ?OidcAccessTokenInterface;

}
