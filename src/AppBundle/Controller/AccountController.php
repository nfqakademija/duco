<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserResults;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AccountController
 * @package AppBundle\Controller
 */
class AccountController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $events = $this->getDoctrine()
            ->getRepository('AppBundle:Result')
            ->getAllResultsByName($user->getFirstName(), $user->getLastName());
        $img = $this->getDoctrine()
            ->getRepository('AppBundle:Facebook')
            ->findOneBy(['userId' => $this->getUser()->getId()])
            ->getFacebookProfileImgUrl();
        if ($img == null) {
            $img = $this->getDoctrine()
                ->getRepository('AppBundle:Google')
                ->findOneBy(['userId' => $this->getUser()->getId()])
                ->getGoogleProfileImgUrl();
        }

        return $this->render('AppBundle:Profile:index.html.twig', [
            'user' => $user,
            'profile_img' => $img,
            'results' => $events,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $addedResults = $this->getDoctrine()
            ->getRepository('AppBundle:UserResults')
            ->findBy(['userId' => $this->getUser()->getId()]);
        foreach ($addedResults as $result) {
            $em->remove($result);
        }
        $request = Request::createFromGlobals();
        $checkedValues = $request->request->get('checked', '');
        foreach ($checkedValues as $value) {
            $userResults = new UserResults();
            $userResults->setUserId($this->getUser()->getId());
            $userResults->setResultId($value);
            $em->persist($userResults);
        }
        $em->flush();

        return $this->redirectToRoute('profile_show_results');
    }
}
