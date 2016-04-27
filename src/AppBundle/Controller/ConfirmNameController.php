<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ConfirmNameController
 * @package AppBundle\Controller
 */
class ConfirmNameController extends Controller
{
    /**
     * Name check page index action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $user = $this->getUser();
        if (!$user->isNameConfirmed()) {
            return $this->render('AppBundle:ConfirmName:index.html.twig', array(
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
            ));
        } else {
            return $this->redirectToRoute('app.index');
        }
    }

    /**
     * Name check submit button click action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function submitAction()
    {
        $request = Request::createFromGlobals();
        $firstName = $request->request->get('form-first-name', '');
        $lastName = $request->request->get('form-last-name', '');
        $user = $this->getUser();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setNameConfirmed(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app.index');
    }
}
