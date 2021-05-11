<?php


namespace HalloVerden\Security\Services;


use HalloVerden\Security\AccessDefinitions\Metadata\AccessDefinitionMetadata;
use HalloVerden\Security\AccessTokenAuthenticator;
use HalloVerden\Security\ClientCredentialsAccessTokenAuthenticator;
use HalloVerden\Security\Interfaces\AccessDefinitionAccessDeciderServiceInterface;
use HalloVerden\Security\Interfaces\SecurityInterface;
use HalloVerden\Security\Voters\AuthenticationVoter;
use HalloVerden\Security\Voters\OauthAuthorizationVoter;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Class AccessDefinitionAccessDeciderService
 *
 * @package HalloVerden\Security\Services
 */
class AccessDefinitionAccessDeciderService implements AccessDefinitionAccessDeciderServiceInterface {

  /**
   * @var SecurityInterface
   */
  private $security;

  /**
   * @var array
   */
  private $scopeableAuthenticators;

  /**
   * @var ExpressionLanguage
   */
  private $expressionLanguage;

  /**
   * AccessDefinitionAccessDeciderService constructor.
   *
   * @param SecurityInterface $security
   * @param array|null        $scopeableAuthenticators
   */
  public function __construct(SecurityInterface $security, ?array $scopeableAuthenticators = null, ?ExpressionLanguage $expressionLanguage = null) {
    $this->scopeableAuthenticators = $scopeableAuthenticators ?? [AccessTokenAuthenticator::class, ClientCredentialsAccessTokenAuthenticator::class];
    $this->security = $security;
    $this->expressionLanguage = $expressionLanguage ?: new ExpressionLanguage();
  }

  /**
   * @inheritDoc
   */
  public function hasAccessDefinedAccess(?AccessDefinitionMetadata $metadata): bool {
    // Nothing specified = access NOT granted.
    if (null === $metadata || (null === $metadata->method && null === $metadata->scopes && null === $metadata->roles && null === $metadata->expression)) {
      return false;
    }

    // If a method is defined, this takes precedence if true.
    if (null !== $metadata->method && is_callable($metadata->method) && ($metadata->method)($metadata)) {
      return true;
    }

    if (null !== $metadata->expression && $this->expressionLanguage->evaluate($metadata->expression, ['metadata' => $metadata])) {
      return true;
    }

    if ($this->shouldCheckScope()) {
      return $metadata->scopes !== null && $this->security->isGranted(OauthAuthorizationVoter::OAUTH_SCOPE, $metadata->scopes);
    }

    return null !== $metadata->roles && $this->security->isGrantedEitherOf($metadata->roles);
  }

  /**
   * @return bool
   */
  private function shouldCheckScope(): bool {
    // If we where authenticated with a "scopeable" authenticator, we should check scopes.
    return $this->security->isGranted(AuthenticationVoter::AUTHENTICATOR, $this->scopeableAuthenticators);
  }

}
