<?php


namespace HalloVerden\Security\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface AuthenticatorDeciderServiceInterface {
  public function canUseAuthenticator(Request $request, string $authenticatorClass, bool $specificAuthenticatorRequired): bool;
}
