<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SearchController
 * @package AppBundle\Controller
 */
class SearchController extends Controller
{
    /**
     * Results search action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $request = Request::createFromGlobals();
        $request->getPathInfo();
        $query = $request->query->get('q', '');
        $results = $this->getDoctrine()->getManager()->getRepository('AppBundle:Result')
            ->getAllResultsBySearchQuery($query);

        return $this->render('AppBundle:Search:index.html.twig', [
            'searchResults' => $results,
        ]);
    }
}
