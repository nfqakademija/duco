<?php

namespace AppBundle\Providers;

use Ddeboer\DataImport\Reader\ExcelReader;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\ItemConverter\CallbackItemConverter;
use Ddeboer\DataImport\ItemConverter\MappingItemConverter;
use Ddeboer\DataImport\Filter\CallbackFilter;
use Ddeboer\DataImport\Workflow;
use AppBundle\ValueConverter\FloatToTimeConverter;

/**
 * Class KaunasMaratonas2015
 * @package AppBundle\Providers
 */
class KaunasMaratonas2015 implements ProviderInterface
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
            ->addValueConverter('netTime', new FloatToTimeConverter())
            ->addValueConverter('finishTime', new FloatToTimeConverter())
            ->addItemConverter($this->getAddConverter())
            ->addFilter($this->getRowFilter())
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
        $reader = new ExcelReader($file, $this->getEvent()->getColumnOffset(), $this->getEvent()->getSheet());
        return $reader;
    }

    /**
     * Downloads data and puts in local file and returns path to that local file
     *
     * @return string
     */
    protected function getFilePath()
    {
        $fileType = $this->getEvent()->getSourceType();
        $path = $this->getServiceContainer()->get('kernel')->getRootDir() . '/Resources/files/Maratonas.' . $fileType;
        file_put_contents($path, file_get_contents($this->getEvent()->getSource()));
        return $path;
    }

    /**
     * Returns original column name before conversion
     *
     * @param $columns
     * @param $name
     * @return mixed|null
     */
    protected function getColumnName($columns, $name)
    {
        while ($current = current($columns)) {
            if ($current === $name) {
                return key($columns);
            }
            next($columns);
        }
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
     * Set to event id and distance suitable values
     *
     * @return CallbackItemConverter
     */
    protected function getAddConverter()
    {
        return new CallbackItemConverter(function ($item) {
            $item['eventId'] = $this->getEvent()->getId();
            $item['distance'] = $this->getEvent()->getDistance();
            return $item;
        });
    }

    /**
     * Checks if there are no null values on specific columns
     *
     * @return CallbackFilter
     */
    protected function getRowFilter()
    {
        return new CallbackFilter(function ($item) {
            $overallPosition = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'overallPosition');
            $finishTime = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'finishTime');
            $netTime = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'netTime');
            return (!is_null($item[$overallPosition]) || !is_null($item[$finishTime]) || !is_null($item[$netTime]));
        });
    }

    /**
     * Writes data to database and disables truncating
     *
     * @return DoctrineWriter
     */
    protected function getDoctrineWriter()
    {
        $doctrineWriter = new DoctrineWriter($this->entityManager, 'AppBundle:Result', array('raceNumber', 'eventId'));
        $doctrineWriter->disableTruncate();
        return $doctrineWriter;
    }
}
