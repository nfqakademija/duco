<?php
/**
 * Created by PhpStorm.
 * User: mind
 * Date: 16.5.5
 * Time: 23.41
 */

namespace AppBundle\Models;

use AppBundle\Entity\Result;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Event model
 */
class Event
{
    /**
     * Event results
     *
     * @var ArrayCollection
     */
    protected $results;

    /**
     * Adds event result
     *
     * @param Result $result
     */
    public function addResult($result)
    {
        if ($this->results == null) {
            $this->results = new ArrayCollection();
        }
        $this->results->add($result);
    }

    /**
     * @return ArrayCollection
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param ArrayCollection $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }
}
