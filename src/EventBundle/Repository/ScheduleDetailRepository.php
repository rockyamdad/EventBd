<?php

namespace EventBundle\Repository;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;
use EventBundle\Entity\Event;

/**
 * CountryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ScheduleDetailRepository extends EntityRepository
{
    public function create($data)
    {
        $this->_em->persist($data);
        $this->_em->flush();
    }
    public function update($data)
    {
        $this->_em->persist($data);
        $this->_em->flush();
    }
    public function delete($data)
    {
        $this->_em->remove($data);
        $this->_em->flush();
    }

    public function getAllByEventDate(Event $event, $date)
    {
        $qb = $this->createQueryBuilder('d');

        return $qb
            ->where($qb->expr()->eq('d.scheduleDate', ':date'))
            ->andWhere($qb->expr()->eq('d.event', ':event'))
            ->setParameter('date', $date, Type::DATE)
            ->setParameter('event', $event)
            ->getQuery()
            ->getResult()
        ;
    }

}
