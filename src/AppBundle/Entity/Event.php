<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Event
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(name="events")
 */
class Event
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="event_name", type="string", length=255, nullable=false)
     */
    protected $eventName;

    /**
     * @var int
     * @ORM\Column(name="year", type="integer", nullable=false)
     */
    protected $year;

    /**
     * @var string
     * @ORM\Column(name="source", type="string", length=255, nullable=false)
     */
    protected $source;

    /**
     * @var string
     * @ORM\Column(name="source_type", type="string", length=255, nullable=false)
     */
    protected $sourceType;

    /**
     * @var int
     * @ORM\Column(name="distance", type="integer", nullable=false)
     */
    protected $distance;

    /**
     * @var int
     * @ORM\Column(name="column_offset", type="integer", nullable=false)
     */
    protected $columnOffset;

    /**
     * @var string
     * @ORM\Column(name="columns", type="string", length=255, nullable=false)
     */
    protected $columns;

    /**
     * @var int
     * @ORM\Column(name="sheet", type="integer", nullable=false)
     */
    protected $sheet;

    /**
     * @var string
     * @ORM\Column(name="provider_name", type="string", length=255, nullable=false)
     */
    protected $providerName;

    /**
     * @var int
     * @ORM\Column(name="data_imported", type="integer", nullable=false)
     */
    protected $dataImported;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @param string $eventName
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getSourceType()
    {
        return $this->sourceType;
    }

    /**
     * @param string $sourceType
     */
    public function setSourceType($sourceType)
    {
        $this->sourceType = $sourceType;
    }

    /**
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param int $distance
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;
    }

    /**
     * @return int
     */
    public function getColumnOffset()
    {
        return $this->columnOffset;
    }

    /**
     * @param int $columnOffset
     */
    public function setColumnOffset($columnOffset)
    {
        $this->columnOffset = $columnOffset;
    }

    /**
     * @return string
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param string $columns
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    /**
     * @return int
     */
    public function getSheet()
    {
        return $this->sheet;
    }

    /**
     * @param int $sheet
     */
    public function setSheet($sheet)
    {
        $this->sheet = $sheet;
    }

    /**
     * @return string
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * @param string $providerName
     */
    public function setProviderName($providerName)
    {
        $this->providerName = $providerName;
    }

    /**
     * @return int
     */
    public function getDataImported()
    {
        return $this->dataImported;
    }

    /**
     * @param int $dataImported
     */
    public function setDataImported($dataImported)
    {
        $this->dataImported = $dataImported;
    }
}
