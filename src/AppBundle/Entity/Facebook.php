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
 * @ORM\Table(name="facebook_users")
 */
class Facebook
{
    /**
     * @ORM\Column(name="user_id", type="integer")
     */
    protected $userId;

    /**
     * @ORM\Id
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=false)
     */
    protected $facebookId;

    /**
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebookAccessToken;

    /**
     * @ORM\Column(name="facebook_profile_img_url", type="string", length=255, nullable=true)
     */
    protected $facebookProfileImgUrl;

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
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param mixed $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param string $facebookAccessToken
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    /**
     * @return string
     */
    public function getFacebookProfileImgUrl()
    {
        return $this->facebookProfileImgUrl;
    }

    /**
     * @param string $facebookProfileImgUrl
     */
    public function setFacebookProfileImgUrl($facebookProfileImgUrl)
    {
        $this->facebookProfileImgUrl = $facebookProfileImgUrl;
    }
}
