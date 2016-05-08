<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\Type\RegistrationFormType;

/**
 * Class HomeController
 * @package AppBundle\Controller
 */
class HomeController extends Controller
{
    /**
     * Home page index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('account_home');
        }

        $form = $this->createForm(RegistrationFormType::class);
        return $this->render('AppBundle:Home:index.html.twig', array(
            'form' => $form->createView(),
            'resultCount' => $this->getDoctrine()->getRepository('AppBundle:Result')->getResultCount(),
            'eventCount' => $this->getDoctrine()->getRepository('AppBundle:Event')->getEventCount()
        ));
    }
}
