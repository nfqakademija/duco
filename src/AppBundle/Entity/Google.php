<?php
/**
 * Created by PhpStorm.
 * User: julius
 * Date: 16.4.8
 * Time: 11.57
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="google_users")
 */
class Google
{
    /**
     * @ORM\Column(name="user_id", type="integer")
     */
    protected $userId;

    /**
     * @ORM\Id
     * @ORM\Column(name="google_id", type="string", length=255, nullable=false)
     */
    protected $googleId;

    /**
     * @ORM\Column(name="google_access_token", type="string", length=255, nullable=true)
     */
    protected $googleAccessToken;

    /**
     * @ORM\Column(name="google_profile_img_url", type="string", length=255, nullable=true)
     */
    protected $googleProfileImgUrl;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * @param mixed $googleId
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;
    }

    /**
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->googleAccessToken;
    }

    /**
     * @param string $googleAccessToken
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->googleAccessToken = $googleAccessToken;
    }

    /**
     * @return string
     */
    public function getProfileImgUrl()
    {
        return $this->googleProfileImgUrl;
    }

    /**
     * @param string $googleProfileImgUrl
     */
    public function setProfileImgUrl($googleProfileImgUrl)
    {
        $this->googleProfileImgUrl = $googleProfileImgUrl;
    }
}
