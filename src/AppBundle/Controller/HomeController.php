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
            return $this->redirectToRoute('profile_show_results');
        }

        $form = $this->createForm(RegistrationFormType::class);
        return $this->render('AppBundle:Home:index.html.twig', array(
            'form' => $form->createView(),
            'resultCount' => $this->getResultCount(),
            'eventCount' => $this->getEventCount()
        ));
    }

    public function getResultCount()
    {
        $query = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
        $query->select('count(r)')->from('AppBundle:Result', 'r');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function getEventCount()
    {
        $query = $this->getDoctrine()->getEntityManager()->createQueryBuilder();
        $query->select('count(e)')->from('AppBundle:Event', 'e');
        return $query->getQuery()->getSingleScalarResult();
    }
}
