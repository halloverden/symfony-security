<?php


namespace HalloVerden\Security\Interfaces;


use HalloVerden\Contracts\Oidc\Tokens\OidcAccessTokenInterface;
use Jose\Easy\JWT;

interface OauthTokenProviderServiceInterface {

  /**
   * @param JWT    $jwt
   * @param string $rawToken
   *
   * @return OidcAccessTokenInterface|null
   */
  public function getOauthTokenFromJWT(JWT $jwt, string $rawToken): ?OidcAccessTokenInterface;

}
