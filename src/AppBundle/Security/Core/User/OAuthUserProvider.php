<?php
namespace AppBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\User\UserInterface;
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
        if ($user === null)
        {
            $user = $this->userManager->createUser();
            $user->setUsername($response->getUsername());
            $user->setEmail($email);
            $user->setFirstName($response->getFirstName());
            $user->setLastName($response->getLastName());
            $user->setPlainPassword(md5(uniqid()));
            $user->setEnabled(true);

            $this->userManager->updateUser($user);

            //$this->createSocialUser($service, $user->getId(), $response->getUsername(), $response->getAccessToken());
            switch ($service) {
                case 'google':
                    $socialUser = new Google();
                    $socialUser->setUserId($user->getId());
                    $socialUser->setGoogleId($socialId);
                    $socialUser->setGoogleAccessToken($response->getAccessToken());
                    break;
                case 'facebook':
                    $socialUser = new Facebook();
                    $socialUser->setUserId($user->getId());
                    $socialUser->setFacebookId($socialId);
                    $socialUser->setFacebookAccessToken($response->getAccessToken());
                    break;
            }
            $this->entityManager->persist($socialUser);
            $this->entityManager->flush();
        }
        else
        {
            $socialUser = $this->entityManager->getRepository('AppBundle:'.ucfirst($service))->find($socialId);

            if ($socialUser === null)
            {
                switch ($service) {
                    case 'google':
                        $socialUser = new Google();
                        $socialUser->setUserId($user->getId());
                        $socialUser->setGoogleId($socialId);
                        $socialUser->setGoogleAccessToken($response->getAccessToken());
                        break;
                    case 'facebook':
                        $socialUser = new Facebook();
                        $socialUser->setUserId($user->getId());
                        $socialUser->setFacebookId($socialId);
                        $socialUser->setFacebookAccessToken($response->getAccessToken());
                        break;
                }
            }
            $this->entityManager->persist($socialUser);
            $this->entityManager->flush();

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
    private function createSocialUser($service, $userId, $socialId, $accessToken)
    {
        switch ($service) {
            case 'google':
                $googleUser = new Google();
                $googleUser->setUserId($userId);
                $googleUser->setGoogleId($socialId);
                $googleUser->setGoogleAccessToken($accessToken);
                break;
            case 'facebook':
                $facebookUser = new Facebook();
                $facebookUser->setUserId($userId);
                $facebookUser->setFacebookId($socialId);
                $facebookUser->setFacebookAccessToken($accessToken);
                break;
        }
    }
}