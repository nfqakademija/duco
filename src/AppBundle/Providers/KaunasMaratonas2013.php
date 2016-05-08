<?php

namespace AppBundle\Providers;

use Ddeboer\DataImport\Reader\ExcelReader;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\ItemConverter\CallbackItemConverter;
use Ddeboer\DataImport\ItemConverter\MappingItemConverter;
use Ddeboer\DataImport\Filter\CallbackFilter;
use Ddeboer\DataImport\Filter\OffsetFilter;
use Ddeboer\DataImport\Workflow;
use AppBundle\ValueConverter\FloatToTimeConverter;

class KaunasMaratonas2013 implements ProviderInterface
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
        $reader = $this->getReader();
        $workFlow = new Workflow($reader);
        $workFlow
            ->addFilter(new OffsetFilter(1))
            ->addFilter($this->getFirstRowFilter())
            ->addFilter($this->getSecondRowFilter())
            ->addItemConverter($this->getColumnConverter())
            ->addItemConverter($this->getNameConverter())
            ->addItemConverter($this->getTimeTrimConverter())
            ->addItemConverter($this->getNetTimeConverter())
            ->addItemConverter($this->getAddConverter())
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
        $reader->setColumnHeaders($this->getColumnHeaders($reader));
        return $reader;
    }

    /**
     * Returns column array with two merged rows
     *
     * @param $reader
     * @return array
     */
    protected function getColumnHeaders($reader)
    {
        $columns = $reader->getColumnHeaders();
        $reader->setHeaderRowNumber($this->getEvent()->getColumnOffset() - 1);
        $columnsAdd = $reader->getColumnHeaders();
        $columns[0] = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'raceNumber');
        $columns[1] = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'firstName');
        $columns[2] = $columnsAdd[2];
        return $columns;
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
        return null;
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
     * Trims finish time value where ',' is
     *
     * @return CallbackItemConverter
     */
    protected function getTimeTrimConverter()
    {
        return new CallbackItemConverter(function ($item) {
            $item['finishTime'] = substr($item['finishTime'], 0, strpos($item['finishTime'], ','));
            return $item;
        });
    }

    /**
     * Copies finish time to net time if data doesn't have net time value
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
     * Checks if overall position column doesn't have 'DNF' value
     *
     * @return CallbackFilter
     */
    protected function getFirstRowFilter()
    {
        return new CallbackFilter(function ($item) {
            $overallPosition = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'overallPosition');
            return $item[$overallPosition] != 'DNF';
        });
    }

    /**
     * Checks if overall position column isn't null
     *
     * @return CallbackFilter
     */
    protected function getSecondRowFilter()
    {
        return new CallbackFilter(function ($item) {
            $overallPosition = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'overallPosition');
            return !is_null($item[$overallPosition]);
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
