<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Models;

use FOS\UserBundle\Model\User as FOSUser;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Storage agnostic user object
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
abstract class User extends FOSUser
{
    /**
     * @return string
     */
    public function getProfilePicture()
    {
        $session = new Session();

        return $session->get('profilePicture');
    }

    /**
     * @param string $profilePicture
     */
    public function setProfilePicture($profilePicture)
    {
        $session = new Session();

        $session->set('profilePicture', $profilePicture);
    }
}
