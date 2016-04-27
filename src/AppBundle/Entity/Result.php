<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Marathoner
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="results")
 */
class Result
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     * @ORM\Column(name="event_id", type="integer", nullable=true)
     */
    protected $eventId;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     * @ORM\Column(name="race_number", type="string", length=255, nullable=true)
     */
    protected $raceNumber;

    /**
     * @var string
     * @ORM\Column(name="finish_time", type="string", nullable=true)
     */
    protected $finishTime;

    /**
     * @var string
     * @ORM\Column(name="net_time", type="string", nullable=true)
     */
    protected $netTime;

    /**
     * @var int
     * @ORM\Column(name="overall_position", type="integer", nullable=true)
     */
    protected $overallPosition;

    /**
     * @var string
     * @ORM\Column(name="club", type="string", length=255, nullable=true)
     */
    protected $club;

    /**
     * @var int
     * @ORM\Column(name="distance", type="integer", nullable=true)
     */
    protected $distance;

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
     * @return int
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @param int $eventId
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getRaceNumber()
    {
        return $this->raceNumber;
    }

    /**
     * @param string $raceNumber
     */
    public function setRaceNumber($raceNumber)
    {
        $this->raceNumber = $raceNumber;
    }

    /**
     * @return mixed
     */
    public function getFinishTime()
    {
        return $this->finishTime;
    }

    /**
     * @param mixed $finishTime
     */
    public function setFinishTime($finishTime)
    {
        $this->finishTime = $finishTime;
    }

    /**
     * @return string
     */
    public function getNetTime()
    {
        return $this->netTime;
    }

    /**
     * @param string $netTime
     */
    public function setNetTime($netTime)
    {
        $this->netTime = $netTime;
    }

    /**
     * @return int
     */
    public function getOverallPosition()
    {
        return $this->overallPosition;
    }

    /**
     * @param int $overallPosition
     */
    public function setOverallPosition($overallPosition)
    {
        $this->overallPosition = $overallPosition;
    }

    /**
     * @return string
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * @param string $club
     */
    public function setClub($club)
    {
        $this->club = $club;
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
}
