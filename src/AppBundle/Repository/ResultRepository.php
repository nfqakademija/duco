<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Event;
use AppBundle\Entity\Result;
use Doctrine\ORM\Query\Expr\Join;

/**
 * ResultRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResultRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param string $firstName
     * @param string $lastName
     * @return null
     */
    public function getAllResultsByName($firstName, $lastName)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select(['r', 'e'])
            ->from(Result::class, 'r')
            ->innerJoin(Event::class, 'e', Join::WITH, 'e.id = r.eventId')
            ->where('r.firstName = :firstName')
            ->andWhere('r.lastName = :lastName');
        $qb->setParameters([
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]);
        $data = $qb->getQuery()->getResult();
        $events = null;
        $results = null;
        if ($data != null) {
            foreach ($data as $row) {
                if (get_class($row) == "AppBundle\\Entity\\Result") {
                    $results[] = $row;
                } else {
                    $events[$row->getId()] = $row;
                }
            }
            foreach ($results as $result) {
                $events[$result->getEventId()]->addResult($result);
            }
        }

        return $events;
    }
}