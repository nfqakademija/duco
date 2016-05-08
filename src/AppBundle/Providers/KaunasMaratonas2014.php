<?php

namespace AppBundle\Providers;

use Ddeboer\DataImport\Reader\ExcelReader;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Ddeboer\DataImport\ItemConverter\CallbackItemConverter;
use Ddeboer\DataImport\ItemConverter\MappingItemConverter;
use Ddeboer\DataImport\Filter\CallbackFilter;
use Ddeboer\DataImport\Workflow;
use AppBundle\ValueConverter\FloatToTimeConverter;

class KaunasMaratonas2014 implements ProviderInterface
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

    public function import()
    {
        $workFlow = new Workflow($this->getReader());
        $workFlow
            ->addItemConverter($this->getColumnConverter())
            ->addItemConverter($this->getNameConverter())
            ->addValueConverter('netTime', new FloatToTimeConverter())
            ->addValueConverter('finishTime', new FloatToTimeConverter())
            ->addItemConverter($this->getAddConverter())
            ->addFilter($this->getRowFilter())
            ->addWriter($this->getDoctrineWriter())
            ->process();
    }

    protected function getReader()
    {
        $file = new \SplFileObject($this->getFilePath());
        $reader = new ExcelReader($file, $this->getEvent()->getColumnOffset(), $this->getEvent()->getSheet());
        return $reader;
    }

    protected function getFilePath()
    {
        $fileType = $this->getEvent()->getSourceType();
        $path = $this->getServiceContainer()->get('kernel')->getRootDir() . '/Resources/files/Maratonas.' . $fileType;
        file_put_contents($path, file_get_contents($this->getEvent()->getSource()));
        return $path;
    }

    protected function getColumnName($columns, $name)
    {
        while ($current = current($columns)) {
            if ($current === $name) {
                return key($columns);
            }
            next($columns);
        }
    }

    protected function getColumnConverter()
    {
        return new MappingItemConverter(unserialize($this->getEvent()->getColumns()));
    }

    protected function getNameConverter()
    {
        return new CallbackItemConverter(function ($item) {
            $position = strpos($item['firstName'], ' ');
            $item['lastName'] = substr($item['firstName'], $position+1);
            $item['firstName'] = substr($item['firstName'], 0, $position);
            return $item;
        });
    }

    protected function getAddConverter()
    {
        return new CallbackItemConverter(function ($item) {
            $item['eventId'] = $this->getEvent()->getId();
            $item['distance'] = $this->getEvent()->getDistance();
            return $item;
        });
    }

    protected function getRowFilter()
    {
        return new CallbackFilter(function ($item) {
            $overallPosition = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'overallPosition');
            $finishTime = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'finishTime');
            $netTime = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'netTime');
            return (!is_null($item[$overallPosition]) || !is_null($item[$finishTime]) || !is_null($item[$netTime]));
        });
    }

    protected function getDoctrineWriter()
    {
        $doctrineWriter = new DoctrineWriter($this->entityManager, 'AppBundle:Result', array('raceNumber', 'eventId'));
        $doctrineWriter->disableTruncate();
        return $doctrineWriter;
    }
}
