<?php


namespace HalloVerden\Security\Event;


use HalloVerden\Security\AbstractAccessTokenAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class AccessTokenCredentialsCheckEvent
 *
 * Dispatched on {@see AbstractAccessTokenAuthenticator::checkCredentials()}.
 *   Throw {@see AuthenticationException} to fail the check.
 *
 * @package HalloVerden\Security\Event
 */
class AccessTokenCredentialsCheckEvent extends Event {

  /**
   * @var mixed
   */
  private $credentials;

  /**
   * @var UserInterface
   */
  private $user;

  /**
   * AccessTokenCredentialsCheckEvent constructor.
   *
   * @param mixed         $credentials
   * @param UserInterface $user
   */
  public function __construct($credentials, UserInterface $user) {
    $this->credentials = $credentials;
    $this->user = $user;
  }

  /**
   * @return mixed
   */
  public function getCredentials() {
    return $this->credentials;
  }

  /**
   * @param mixed $credentials
   */
  public function setCredentials($credentials): void {
    $this->credentials = $credentials;
  }

  /**
   * @return UserInterface
   */
  public function getUser(): UserInterface {
    return $this->user;
  }

  /**
   * @param UserInterface $user
   */
  public function setUser(UserInterface $user): void {
    $this->user = $user;
  }

}
