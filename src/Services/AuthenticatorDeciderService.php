<?php


namespace HalloVerden\Security\Services;

use Doctrine\Common\Annotations\Reader;
use HalloVerden\Security\Annotations\Authenticator;
use HalloVerden\Security\Interfaces\AuthenticatorDeciderServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthenticatorDeciderService implements AuthenticatorDeciderServiceInterface {

  /**
   * @var Reader
   */
  private $reader;

  /**
   * @var array<string, Authenticator>
   */
  private $authenticatorAnnotations = [];

  /**
   * AuthenticatorDeciderService constructor.
   *
   * @param Reader $reader
   */
  public function __construct(Reader $reader) {
    $this->reader = $reader;
  }

  /**
   * @param Request $request
   * @param string  $authenticatorClass
   * @param bool    $specificAuthenticatorRequired
   *
   * @return bool
   * @throws \ReflectionException
   */
  public function canUseAuthenticator(Request $request, string $authenticatorClass, bool $specificAuthenticatorRequired): bool {
    if (!$request->attributes->has('_controller')) {
      throw new \RuntimeException("Request attributes is missing '_controller' attribute. Method must be called after ResolveControllerNameSubscriber.");
    };

    $authenticator = $this->getAuthenticatorAnnotation($request->attributes->get('_controller'));

    if ($authenticator === null) {
      return !$specificAuthenticatorRequired;
    }

    return in_array($authenticatorClass, $authenticator->authenticators);
  }

  /**
   * @param string $controllerMethod
   *
   * @return Authenticator
   * @throws \ReflectionException
   */
  private function getAuthenticatorAnnotation(string $controllerMethod): ?Authenticator {
    if (array_key_exists($controllerMethod,  $this->authenticatorAnnotations)) {
      return $this->authenticatorAnnotations[$controllerMethod];
    }

    /** @var Authenticator $authenticator */
    $authenticator = $this->reader->getMethodAnnotation(new \ReflectionMethod($controllerMethod), Authenticator::class);
    return $this->authenticatorAnnotations[$controllerMethod] = $authenticator;
  }

}
