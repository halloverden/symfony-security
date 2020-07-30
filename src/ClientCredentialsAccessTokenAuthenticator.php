<?php


namespace HalloVerden\Security;


use HalloVerden\Contracts\Oidc\Tokens\OidcTokenInterface;

class ClientCredentialsAccessTokenAuthenticator extends AbstractAccessTokenAuthenticator {

  /**
   * @inheritDoc
   */
  protected function getTokenType(): string {
    return OidcTokenInterface::TYPE_ACCESS_CLIENT_CREDENTIALS;
  }

}
