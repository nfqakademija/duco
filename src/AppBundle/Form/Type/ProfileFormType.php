<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileFormType extends BaseFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('username')
            ->remove('plainPassword')
            ->remove('current_password')
            ->remove('email');
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'AppBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
//         return 'app_user_profile';
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
