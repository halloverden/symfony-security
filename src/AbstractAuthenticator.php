<?php


namespace HalloVerden\Security;

use HalloVerden\HttpExceptions\UnauthorizedException;
use HalloVerden\Security\Interfaces\AuthenticatorDeciderServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Class AbstractAuthenticator
 *
 * @package App\Security
 */
abstract class AbstractAuthenticator extends AbstractGuardAuthenticator {

  /**
   * @var AuthenticatorDeciderServiceInterface
   */
  private $authenticatorDeciderService;

  /**
   * @param Request $request
   *
   * @return bool
   */
  protected abstract function _supports(Request $request): bool;

  /**
   * @param Request $request
   *
   * @return bool
   */
  public function supports(Request $request) {
    if (!$this->authenticatorDeciderService->canUseAuthenticator($request, get_class($this), $this->specificAuthenticatorRequired())) {
      return false;
    }

    return $this->_supports($request);
  }

  /**
   * If true, an authenticator has to be specified on the route for it to use the authenticator.
   *
   * @return bool
   */
  protected function specificAuthenticatorRequired(): bool {
    return false;
  }

  /**
   * @param Request                      $request
   * @param AuthenticationException|null $authException
   *
   * @return Response|void
   */
  public function start(Request $request, AuthenticationException $authException = null) {
    throw new UnauthorizedException("NO_ACCESS");
  }

  /**
   * @param Request                 $request
   * @param AuthenticationException $exception
   *
   */
  public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
    throw new UnauthorizedException("INVALID_TOKEN");
  }

  /**
   * @param Request        $request
   * @param TokenInterface $token
   * @param string         $providerKey
   *
   * @return null
   */
  public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
    $token->setAttribute('authenticator', get_class($this));
    return null;
  }

  /**
   * @return bool
   */
  public function supportsRememberMe() {
    return false;
  }

  /**
   * @param AuthenticatorDeciderServiceInterface $authenticatorDeciderService
   *
   * @return AbstractAuthenticator
   */
  public function setAuthenticatorDeciderService(AuthenticatorDeciderServiceInterface $authenticatorDeciderService): self {
    $this->authenticatorDeciderService = $authenticatorDeciderService;
    return $this;
  }

}
