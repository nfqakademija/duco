<?php

namespace AppBundle\Repository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Event quantity
     *
     * @return mixed
     */
    public function getEventCount()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(e) FROM AppBundle:Event e')
            ->getSingleScalarResult();
    }
}
