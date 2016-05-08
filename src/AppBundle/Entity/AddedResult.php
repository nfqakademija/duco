<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AddedResult
 *
 * @ORM\Table(name="added_results")
 * @ORM\Entity
 */
class AddedResult
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="resultId", type="integer")
     */
    private $resultId;

    /**
     * @var int
     *
     * @ORM\Column(name="event_id", type="integer")
     */
    private $eventId;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="race_number", type="string", length=255, nullable=true)
     */
    private $raceNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="finish_time", type="string", length=255, nullable=true)
     */
    private $finishTime;

    /**
     * @var string
     *
     * @ORM\Column(name="net_time", type="string", length=255, nullable=true)
     */
    private $netTime;

    /**
     * @var int
     *
     * @ORM\Column(name="overallPosition", type="integer", nullable=true)
     */
    private $overallPosition;

    /**
     * @var string
     *
     * @ORM\Column(name="club", type="string", length=255, nullable=true)
     */
    private $club;

    /**
     * @var int
     *
     * @ORM\Column(name="distance", type="integer", nullable=true)
     */
    private $distance;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set eventId
     *
     * @param integer $eventId
     *
     * @return AddedResult
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * Get eventId
     *
     * @return int
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Set raceNumber
     *
     * @param string $raceNumber
     *
     * @return AddedResult
     */
    public function setRaceNumber($raceNumber)
    {
        $this->raceNumber = $raceNumber;

        return $this;
    }

    /**
     * Get raceNumber
     *
     * @return string
     */
    public function getRaceNumber()
    {
        return $this->raceNumber;
    }

    /**
     * Set finishTime
     *
     * @param string $finishTime
     *
     * @return AddedResult
     */
    public function setFinishTime($finishTime)
    {
        $this->finishTime = $finishTime;

        return $this;
    }

    /**
     * Get finishTime
     *
     * @return string
     */
    public function getFinishTime()
    {
        return $this->finishTime;
    }

    /**
     * Set netTime
     *
     * @param string $netTime
     *
     * @return AddedResult
     */
    public function setNetTime($netTime)
    {
        $this->netTime = $netTime;

        return $this;
    }

    /**
     * Get netTime
     *
     * @return string
     */
    public function getNetTime()
    {
        return $this->netTime;
    }

    /**
     * Set overallPosition
     *
     * @param integer $overallPosition
     *
     * @return AddedResult
     */
    public function setOverallPosition($overallPosition)
    {
        $this->overallPosition = $overallPosition;

        return $this;
    }

    /**
     * Get overallPosition
     *
     * @return int
     */
    public function getOverallPosition()
    {
        return $this->overallPosition;
    }

    /**
     * Set club
     *
     * @param string $club
     *
     * @return AddedResult
     */
    public function setClub($club)
    {
        $this->club = $club;

        return $this;
    }

    /**
     * Get club
     *
     * @return string
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * Set distance
     *
     * @param integer $distance
     *
     * @return AddedResult
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get distance
     *
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getResultId()
    {
        return $this->resultId;
    }

    /**
     * @param int $resultId
     */
    public function setResultId($resultId)
    {
        $this->resultId = $resultId;
    }
}
