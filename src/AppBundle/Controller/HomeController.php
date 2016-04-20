<?php

namespace AppBundle\Controller;

use Ddeboer\DataImport\Reader\ExcelReader;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\ValueConverter\StringToDateTimeValueConverter;

class HomeController extends Controller
{
    /**
     * Home page index action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $filePath = $this->get('kernel')->getRootDir().'/Resources/files/KaunoMaratonas2015.xls';
        $file = new \SplFileObject($filePath);
        $reader = new ExcelReader($file, 0);
        $replacement = ['firstName', 'lastName', 'raceNumber', '', '', '', '',
                        'overallPosition', '', '', '', '', 'club', '', ''];
        $reader->setColumnHeaders($replacement);
        $workFlow = new Workflow($reader);
        $entityManager = $this->getDoctrine()->getManager();
        $doctrineWriter = new DoctrineWriter($entityManager, 'AppBundle:Marathoner');
        $workFlow->addWriter($doctrineWriter);
        $workFlow->process();
        return $this->render('AppBundle:Home:index.html.twig', array(
            // ...
        ));
    }
}
