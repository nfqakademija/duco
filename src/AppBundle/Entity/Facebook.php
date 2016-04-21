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
    protected $user_id;

    /**
     * @ORM\Id
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=false)
     */
    protected $facebook_id;

    /**
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebook_access_token;

    /**
     * @ORM\Column(name="facebook_profile_img_url", type="string", length=255, nullable=true)
     */
    protected $facebook_profile_img_url;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * @param mixed $facebook_id
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;
    }

    /**
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * @param string $facebook_access_token
     */
    public function setFacebookAccessToken($facebook_access_token)
    {
        $this->facebook_access_token = $facebook_access_token;
    }

    /**
     * @return string
     */
    public function getFacebookProfileImgUrl()
    {
        return $this->facebook_profile_img_url;
    }

    /**
     * @param string $facebook_profile_img_url
     */
    public function setFacebookProfileImgUrl($facebook_profile_img_url)
    {
        $this->facebook_profile_img_url = $facebook_profile_img_url;
    }
    
    
    
}