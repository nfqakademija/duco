<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserResults
 *
 * @ORM\Table(name="user_results")
 * @ORM\Entity
 */
class UserResults
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
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="result_id", type="integer")
     */
    private $resultId;


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
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserResults
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set resultId
     *
     * @param integer $resultId
     *
     * @return UserResults
     */
    public function setResultId($resultId)
    {
        $this->resultId = $resultId;

        return $this;
    }

    /**
     * Get resultId
     *
     * @return int
     */
    public function getResultId()
    {
        return $this->resultId;
    }
}
