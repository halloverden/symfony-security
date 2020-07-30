<?php


namespace HalloVerden\Security;


use HalloVerden\Contracts\Oidc\Tokens\OidcAccessTokenInterface;
use HalloVerden\Security\Entity\Helpers\RequestBearerTokenHelper;
use HalloVerden\Security\Interfaces\ExpirableInterface;
use HalloVerden\Security\Interfaces\OauthAuthenticatorServiceInterface;
use HalloVerden\Security\Interfaces\OauthTokenProviderServiceInterface;
use HalloVerden\Security\Interfaces\OauthUserProviderServiceInterface;
use HalloVerden\Security\Interfaces\RevocableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

abstract class AbstractAccessTokenAuthenticator extends AbstractAuthenticator {
  const ATTRIBUTE_OAUTH_TOKEN = 'OAUTH_TOKEN';

  /**
   * @var OauthAuthenticatorServiceInterface
   */
  private $oauthAuthenticatorService;

  /**
   * @var OauthTokenProviderServiceInterface
   */
  private $oauthTokenProvider;

  /**
   * @var OidcAccessTokenInterface|null
   */
  private $accessToken;

  /**
   * @var OauthUserProviderServiceInterface
   */
  private $oauthUserProviderService;

  /**
   * AbstractAccessTokenAuthenticator constructor.
   *
   * @param OauthAuthenticatorServiceInterface $oauthAuthenticatorService
   * @param OauthTokenProviderServiceInterface $oauthTokenProvider
   * @param OauthUserProviderServiceInterface  $oauthUserProviderService
   */
  public function __construct(OauthAuthenticatorServiceInterface $oauthAuthenticatorService,
                              OauthTokenProviderServiceInterface $oauthTokenProvider,
                              OauthUserProviderServiceInterface $oauthUserProviderService) {
    $this->oauthAuthenticatorService = $oauthAuthenticatorService;
    $this->oauthTokenProvider = $oauthTokenProvider;
    $this->oauthUserProviderService = $oauthUserProviderService;
  }

  /**
   * @return string
   */
  abstract protected function getTokenType(): string;

  /**
   * @inheritDoc
   */
  protected function _supports(Request $request): bool {
    $bearerToken = RequestBearerTokenHelper::getBearerToken($request);

    if (null === $bearerToken) {
      return false;
    }

    try {
      $jwt = $this->oauthAuthenticatorService->validateAndGetAccessToken($bearerToken);
    } catch (\Exception $e) {
      return false;
    }

    if ($jwt->claims->get('type') !== $this->getTokenType()) {
      return false;
    }

    $this->accessToken = $this->oauthTokenProvider->getOauthTokenFromJWT($jwt, $bearerToken);

    return $this->accessToken !== null;
  }

  /**
   * @inheritDoc
   */
  public function getCredentials(Request $request) {
    return $this->accessToken;
  }

  /**
   * @inheritDoc
   * @param OidcAccessTokenInterface $credentials
   */
  public function getUser($credentials, UserProviderInterface $userProvider) {
    $user = $this->oauthUserProviderService->getUserFromAccessToken($credentials);

    if (null === $user) {
      throw new AuthenticationException("User not found");
    }

    return $user;
  }

  /**
   * @inheritDoc
   */
  public function checkCredentials($credentials, UserInterface $user) {
    if ($credentials instanceof RevocableInterface && $credentials->isRevoked()) {
      return false;
    }

    if ($credentials instanceof ExpirableInterface && $credentials->isExpired()) {
      return false;
    }

    return true;
  }

  /**
   * @inheritDoc
   */
  public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
    $token->setAttribute(self::ATTRIBUTE_OAUTH_TOKEN, $this->accessToken);
    return parent::onAuthenticationSuccess($request, $token, $providerKey);
  }


}
