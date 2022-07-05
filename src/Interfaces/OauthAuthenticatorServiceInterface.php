<?php


namespace HalloVerden\Security\Interfaces;


interface OauthAuthenticatorServiceInterface {

  /**
   * @param string $token
   *
   * @return array claims
   * @throws \Exception
   */
  public function validateAndGetAccessToken(string $token): array;

}
