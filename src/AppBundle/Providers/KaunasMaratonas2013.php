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

    public function import()
    {
        $reader = $this->getReader();
        $workFlow = new Workflow($reader);
        $workFlow
            ->addFilter(new OffsetFilter(1))
            ->addItemConverter($this->getColumnConverter())
            ->addItemConverter($this->getNameConverter())
            ->addItemConverter($this->getTimeTrimConverter())
            ->addItemConverter($this->getRowMergeConverter($reader))
            ->addItemConverter($this->getAddConverter())
            ->addValueConverter('netTime', new FloatToTimeConverter())
            ->addFilter($this->getRowFilter())
            ->addWriter($this->getDoctrineWriter())
            ->process();
    }

    protected function getReader()
    {
        $file = new \SplFileObject($this->getFilePath());
        $reader = new ExcelReader($file, $this->getEvent()->getColumnOffset(), $this->getEvent()->getSheet());
        $reader->setColumnHeaders($this->getColumnHeaders($reader));
        return $reader;
    }

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
        return null;
    }

    protected function getColumnConverter()
    {
        $columnConverter = new MappingItemConverter(unserialize($this->getEvent()->getColumns()));
        return $columnConverter;
    }

    protected function getNameConverter()
    {
        $nameConverter = new CallbackItemConverter(function ($item) {
            $position = strpos($item['firstName'], ' ');
            $item['lastName'] = substr($item['firstName'], $position+1);
            $item['firstName'] = substr($item['firstName'], 0, $position);
            return $item;
        });
        return $nameConverter;
    }

    protected function getTimeTrimConverter()
    {
        $timeTrimConverter = new CallbackItemConverter(function ($item) {
            $item['finishTime'] = substr($item['finishTime'], 0, strpos($item['finishTime'], ','));
            return $item;
        });
        return $timeTrimConverter;
    }

    protected function getRowMergeConverter($reader)
    {
        $rowMergeConverter = new CallbackItemConverter(function ($item) use ($reader) {
            $raceNumber = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'raceNumber');
            $i = 0;
            foreach ($reader as $readerItem) {
                if ($readerItem[$raceNumber] === $item['raceNumber'] && is_null($item['netTime'])) {
                    $ind = $i + 1;
                    break;
                }
                $i++;
            }
            $readerItem = $reader->getRow($ind + $this->getEvent()->getColumnOffset());
            $netTime = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'netTime');
            $item['netTime'] = $readerItem[$netTime];
            return $item;
        });
        return $rowMergeConverter;
    }

    protected function getAddConverter()
    {
        $addConverter = new CallbackItemConverter(function ($item) {
            $item['eventId'] = $this->getEvent()->getId();
            $item['distance'] = $this->getEvent()->getDistance();
            return $item;
        });
        return $addConverter;
    }

    protected function getRowFilter()
    {
        $rowFilter = new CallbackFilter(function ($item) {
            $overallPosition = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'overallPosition');
            $finishTime = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'finishTime');
            $netTime = $this->getColumnName(unserialize($this->getEvent()->getColumns()), 'netTime');
            return (!is_null($item[$overallPosition]) || !is_null($item[$finishTime]) || !is_null($item[$netTime]));
        });
        return $rowFilter;
    }

    protected function getDoctrineWriter()
    {
        $doctrineWriter = new DoctrineWriter($this->entityManager, 'AppBundle:Result', array('raceNumber', 'eventId'));
        $doctrineWriter->disableTruncate();
        return $doctrineWriter;
    }
}
