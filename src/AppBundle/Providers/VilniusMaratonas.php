<?php

namespace AppBundle\Providers;

use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\ItemConverter\MappingItemConverter;
use Ddeboer\DataImport\ItemConverter\CallbackItemConverter;

class VilniusMaratonas implements ProviderInterface
{
    protected $event = array();
    protected $entityManager;
    protected $serviceContainer;

    /**
     * @return array
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param array $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param mixed $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return mixed
     */
    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    /**
     * @param mixed $serviceContainer
     */
    public function setServiceContainer($serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * Import data from file to database
     *
     * @throws \Ddeboer\DataImport\Exception\ExceptionInterface
     */
    public function import()
    {
        $workFlow = new Workflow($this->getReader());
        $workFlow
            ->addItemConverter($this->getColumnConverter())
            ->addItemConverter($this->getAddConverter())
            ->addItemConverter($this->getNameConverter())
            ->addItemConverter($this->getTimeTrim())
            ->addItemConverter($this->getNetTimeConverter())
            ->addWriter($this->getDoctrineWriter())
            ->process();

    }

    /**
     * Returns results data from file
     *
     * @return ExcelReader
     */
    protected function getReader()
    {
        $file = new \SplFileObject($this->getFilePath());
        $reader = new CsvReader($file, ';');
        $reader->setHeaderRowNumber(0);
        return $reader;
    }

    /**
     * Copies from content to local file and returns path to that file
     *
     * @return string
     */
    protected function getFilePath()
    {
        $fileType = $this->getEvent()->getSourceType();
        $source = $this->getEvent()->getSource();
        $path = $this->getServiceContainer()->get('kernel')->getRootDir() . '/Resources/files/Maratonas.' . $fileType;
        $file = $this->getServiceContainer()->get('kernel')->getRootDir() . '/Resources/files/' . $source;
        file_put_contents($path, file_get_contents($file));
        return $path;
    }

    /**
     * Unserializes string to array and converts reader's columns
     *
     * @return MappingItemConverter
     */
    protected function getColumnConverter()
    {
        return new MappingItemConverter(unserialize($this->getEvent()->getColumns()));
    }

    /**
     * Set suitable variable value to event id
     *
     * @return CallbackItemConverter
     */
    protected function getAddConverter()
    {
        return new CallbackItemConverter(function ($item) {
            $item['eventId'] = $this->getEvent()->getId();
            return $item;
        });
    }

    /**
     * Separate first name and last name from one column and last name puts in other column
     *
     * @return CallbackItemConverter
     */
    protected function getNameConverter()
    {
        return new CallbackItemConverter(function ($item) {
            $position = strpos($item['firstName'], ' ');
            $item['lastName'] = substr($item['firstName'], $position+1);
            $item['firstName'] = substr($item['firstName'], 0, $position);
            return $item;
        });
    }

    /**
     * From 2010 event deletes milisecond part and trims finish time
     *
     * @return CallbackItemConverter
     */
    protected function getTimeTrim()
    {
        return new CallbackItemConverter(function ($item) {
            if ($item['distance'] === '4.2' && $this->getEvent()->getYear() === 2010) {
                $item['finishTime'] = substr($item['finishTime'], 0, strlen($item['finishTime'])-3);
            } elseif (strpos($item['finishTime'], '.') !== false) {
                $item['finishTime'] = substr($item['finishTime'], 0, strpos($item['finishTime'], '.'));
            }
            return $item;
        });
    }

    /**
     * Copies finish time to net time
     *
     * @return CallbackItemConverter
     */
    protected function getNetTimeConverter()
    {
        return new CallbackItemConverter(function ($item) {
            $item['netTime'] = $item['finishTime'];
            return $item;
        });
    }

    /**
     * Writes data to database and disables truncating
     *
     * @return DoctrineWriter
     */
    protected function getDoctrineWriter()
    {
        $doctrineWriter = new DoctrineWriter(
            $this->entityManager,
            'AppBundle:Result',
            array('finishTime', 'distance', 'lastName', 'firstName', 'overallPosition', 'eventId')
        );
        $doctrineWriter->disableTruncate();
        return $doctrineWriter;
    }
}
