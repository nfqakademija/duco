<?php






/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends BaseController
{
    public function registerAction(Request $request)
    {
        if($request->isMethod('POST')){

            /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
            $formFactory = $this->get('fos_user.registration.form.factory');
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $user = $userManager->createUser();
            $user->setEnabled(true);

            $form = $formFactory->createForm();
            $form->setData($user);

            $form->handleRequest($request);

            $errors = [];
            if(!$form->isValid()) {
                foreach ($form->getErrors(true) as $e) {
                    $errors[] = $e->getMessage();
                }
                $this->get('session')->set('errors', $errors);
                return $this->redirectToRoute('app.index');
            }
        }

        return parent::registerAction($request);

    }
}
