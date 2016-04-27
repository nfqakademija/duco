<?php
namespace AppBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\User\UserChecker;
use FOS\UserBundle\Doctrine;
use AppBundle\Entity\Google;
use AppBundle\Entity\Facebook;

/**
 * Class OAuthUserProvider
 * @package AppBundle\Security\Core\User
 */
class OAuthUserProvider extends BaseClass
{
    private $entityManager;

    /**
     * OAuthUserProvider constructor.
     * @param UserManagerInterface $userManager
     * @param array $properties
     * @param $entityManager
     */
    public function __construct(UserManagerInterface $userManager, array $properties, $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($userManager, $properties);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $email = $response->getEmail();
        $socialId = $response->getUsername();
        $user = $this->userManager->findUserByEmail($email);
        $service = $response->getResourceOwner()->getName();
        $accessToken = $response->getAccessToken();
        $profilePicture = $response->getProfilePicture();
        if ($user === null) {
            $user = $this->userManager->createUser();
            $user->setUsername($response->getUsername());
            $user->setEmail($email);
            $user->setFirstName($response->getFirstName());
            $user->setLastName($response->getLastName());
            $user->setPlainPassword(md5(uniqid()));
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
            $this->createSocialUser($service, $user->getId(), $socialId, $accessToken, $profilePicture);
        } else {
            $socialUser = $this->entityManager->getRepository('AppBundle:'.ucfirst($service))->find($socialId);
            if ($socialUser === null) {
                $this->createSocialUser($service, $user->getId(), $socialId, $accessToken, $profilePicture);
            }
            $checker = new UserChecker();
            $checker->checkPreAuth($user);
        }
        return $user;
    }

    /**
     * @param $service
     * @param $userId
     * @param $socialId
     * @param $accessToken
     */
    private function createSocialUser($service, $userId, $socialId, $accessToken, $profileImgUrl)
    {
        switch ($service) {
            case 'google':
                $socialUser = new Google();
                $socialUser->setUserId($userId);
                $socialUser->setGoogleId($socialId);
                $socialUser->setGoogleAccessToken($accessToken);
                $socialUser->setGoogleProfileImgUrl($profileImgUrl);
                break;
            case 'facebook':
                $socialUser = new Facebook();
                $socialUser->setUserId($userId);
                $socialUser->setFacebookId($socialId);
                $socialUser->setFacebookAccessToken($accessToken);
                $socialUser->setFacebookProfileImgUrl($profileImgUrl);
                break;
            default:
                return;
        }
        $this->entityManager->persist($socialUser);
        $this->entityManager->flush();
    }
}
