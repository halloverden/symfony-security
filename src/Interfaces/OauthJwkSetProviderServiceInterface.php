<?php


namespace HalloVerden\Security\Interfaces;


use Jose\Component\Core\JWKSet;

interface OauthJwkSetProviderServiceInterface {

  /**
   * @param string $issuer
   *
   * @return JWKSet
   */
  public function getPublicKey(string $issuer): JWKSet;

}
