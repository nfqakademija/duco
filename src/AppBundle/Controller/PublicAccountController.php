<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PublicAccountController
 * @package AppBundle\Controller
 */
class PublicAccountController extends Controller
{
    /**
     * @param int $userId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($userId)
    {
        $doc = $this->getDoctrine();
        $results = $doc->getRepository('AppBundle:Result')->getAddedResultsByUserId($userId);
        $user = $doc->getRepository('AppBundle:User')->find($userId);
 
        $profileImg = $doc->getRepository('AppBundle:Facebook')->findOneBy(['userId' => $userId]);
        if ($profileImg == null) {
            $profileImg = $doc->getRepository('AppBundle:Google')->findOneBy(['userId' => $userId]);
        }
        if ($profileImg != null) {
            $profileImg = $profileImg->getProfileImgUrl();
        }

        return $this->render('AppBundle:PublicProfile:index.html.twig', [
            'results' => $results,
            'profilePicture' => $profileImg,
            'user' => $user,
        ]);
    }
}
