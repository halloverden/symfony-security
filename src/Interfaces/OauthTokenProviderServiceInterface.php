<?php


namespace HalloVerden\Security\Interfaces;


use HalloVerden\Contracts\Oidc\Tokens\OidcAccessTokenInterface;
use Jose\Easy\JWT;

interface OauthTokenProviderServiceInterface {

  /**
   * @param JWT $jwt
   *
   * @return OidcAccessTokenInterface|null
   */
  public function getOauthTokenFromJWT(JWT $jwt): ?OidcAccessTokenInterface;

}
