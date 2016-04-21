<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\Type\RegistrationFormType;

class HomeController extends Controller
{
    /**
     * Home page index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $form = $this->createForm(RegistrationFormType::class);
        return $this->render('AppBundle:Home:index.html.twig', array(
            // ...
            'form' => $form->createView()
        ));
    }

}
