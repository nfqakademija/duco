<?php

namespace AppBundle\Command;

use AppBundle\ValueConverter\FloatToTimeConverter;
use Ddeboer\DataImport\ItemConverter\MappingItemConverter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\ValueConverter\StringToDateTimeValueConverter;
use Ddeboer\DataImport\Reader\ExcelReader;
use Ddeboer\DataImport\ItemConverter\CallbackItemConverter;
use Ddeboer\DataImport\ItemConverter\NestedMappingItemConverter;
use Ddeboer\DataImport\Writer\CsvWriter;

class ReadCommand extends ContainerAwareCommand
{
    protected $entityManager;
    protected $container;

    /**
     * ReadCommand constructor.
     * @param null|string $name
     * @param $entityManager
     * @param $container
     */
    public function __construct($name, $entityManager, $container)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    protected function configure()
    {
        $this
            ->setName('read')
            ->setDescription('Read results');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $q = $this->entityManager->getRepository('AppBundle:Event');
        $rows = $q->findAll();
        foreach ($rows as $row) {
            $file = new \SplFileObject($this->getFilePath($row));
            $reader = new ExcelReader($file, $row->getColumnOffset());
            $converter = new CallbackItemConverter(function ($item) use ($row) {
                $item['eventId'] = $row->getId();
                $item['distance'] = $row->getDistance();
                return $item;
            });
            $columnConverter = new MappingItemConverter(unserialize($row->getColumns()));
            $doctrineWriter = new DoctrineWriter($this->entityManager, 'AppBundle:Result');
            $doctrineWriter->disableTruncate();
            $workFlow = new Workflow($reader);
            $workFlow
                ->addItemConverter($columnConverter)
                ->addValueConverter('finishTime', new FloatToTimeConverter())
                ->addItemConverter($converter)
                ->addWriter($doctrineWriter)
                ->process();
        }
    }

    /**
     * @param $row
     * @return string
     */
    protected function getFilePath($row)
    {
        $fileType = $row->getSourceType();
        $filePath = $this->container->get('kernel')->getRootDir() . '/Resources/files/Maratonas.' . $fileType;
        file_put_contents($filePath, file_get_contents($row->getSource()));
        return $filePath;
    }
}
