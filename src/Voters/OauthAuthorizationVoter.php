<?php


namespace HalloVerden\Security\Voters;

use HalloVerden\Security\Interfaces\OauthAuthenticatedAccessTokenProviderServiceInterface;
use HalloVerden\Security\Interfaces\SecurityInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class OauthAuthorizationVoter
 *
 * @package HalloVerden\Security\Voters
 */
class OauthAuthorizationVoter extends BaseVoter {
  const OAUTH_SCOPE = 'oauth.scope';

  /**
   * @var OauthAuthenticatedAccessTokenProviderServiceInterface
   */
  private $accessTokenProvider;

  /**
   * @inheritDoc
   */
  public function __construct(SecurityInterface $security, OauthAuthenticatedAccessTokenProviderServiceInterface $accessTokenProvider) {
    parent::__construct($security);
    $this->accessTokenProvider = $accessTokenProvider;
  }

  /**
   * @return string[]
   */
  protected function getSupportedAttributes(): array {
    return [self::OAUTH_SCOPE];
  }

  /**
   * @return string[]
   */
  protected function getSupportedClasses(): array {
    return ['string'];
  }

  /**
   * @inheritDoc
   */
  protected function voteOnAttribute(string $attribute, $subjects, TokenInterface $token) {
    switch ($attribute) {
      case self::OAUTH_SCOPE:
        return $this->hasOauthScope($this->sortSubjects($subjects, ['string'], false));
    }

    throw new \LogicException('This code should not be reached!');
  }

  /**
   * @param array $scopes
   *
   * @return bool
   */
  private function hasOauthScope(array $scopes): bool {
    $accessToken = $this->accessTokenProvider->getAuthenticatedAccessToken();

    if ($accessToken === null) {
      return false;
    }

    $accessTokenScopes = explode(' ', $accessToken->getScope());

    foreach ($scopes as $scope) {
      if (in_array($scope, $accessTokenScopes)) {
        return true;
      }
    }

    return false;
  }
}
