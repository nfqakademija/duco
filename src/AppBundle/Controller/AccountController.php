<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AddedResult;
use AppBundle\Entity\Result;
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
        $resultRepo = $this->getDoctrine()->getRepository('AppBundle:Result');
        $notAddedResults = $resultRepo->getNotAddedResultsByName($user->getFirstName(), $user->getLastName());
        $addedResults = $resultRepo->getAddedResultsByUserId($user->getId());

        return $this->render('AppBundle:Profile:index.html.twig', [
            'notAddedResults' => $notAddedResults,
            'addedResults' => $addedResults,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = Request::createFromGlobals();
        $checkedValues = $request->request->get('checked', '');
        var_dump($checkedValues);
        foreach ($checkedValues as $id) {
            $checkedResult = $this->getDoctrine()
                ->getRepository('AppBundle:Result')
                ->find($id);
            $em->persist($this->copyResult($checkedResult, $id));
        }
        $em->flush();

        return $this->redirectToRoute('profile_show_results');
    }

    /**
     * @param Result $result
     * @param int    $id
     * @return AddedResult
     */
    public function copyResult($result, $id)
    {
        $resultToAdd = new AddedResult();
        $resultToAdd->setResultId($id);
        $resultToAdd->setUserId($this->getUser()->getId());
        $resultToAdd->setClub($result->getClub());
        $resultToAdd->setDistance($result->getDistance());
        $resultToAdd->setEventId($result->getEventId());
        $resultToAdd->setFinishTime($result->getFinishTime());
        $resultToAdd->setNetTime($result->getNetTime());
        $resultToAdd->setOverallPosition($result->getOverallPosition());
        $resultToAdd->setRaceNumber($result->getRaceNumber());

        return $resultToAdd;
    }
}
