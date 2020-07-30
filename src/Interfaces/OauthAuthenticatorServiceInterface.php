<?php


namespace HalloVerden\Security\Interfaces;


use Jose\Easy\JWT;

interface OauthAuthenticatorServiceInterface {

  /**
   * @param string $token
   *
   * @return JWT
   * @throws \Exception
   */
  public function validateAndGetAccessToken(string $token): JWT;

}
