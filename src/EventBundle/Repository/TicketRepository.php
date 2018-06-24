<?php

namespace EventBundle\Repository;

use Doctrine\ORM\EntityRepository;
use EventBundle\Entity\EventGroup;

/**
 * TicketRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TicketRepository extends EntityRepository
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

    public function getEventsMinimumPriceByEventGroup(EventGroup $group)
    {
        $qb = $this->createQueryBuilder('et');
        $query =$qb
            ->select("MIN(et.price) AS price")
            ->addSelect('e.id as ID')
            ->Join('et.event', 'e')
            ->groupBy('et.event')
            ->where('e.group =:group')
            ->andWhere('e.status =:status')

            ->setParameter('group',$group)
            ->setParameter('status','Activate');
        $result = $query->getQuery()->getResult();

        $data = array();
        foreach($result as $row) {
            $data[$row['ID']] = $row['price'];
        }

        return $data;

    }
    public function getEventsMinimumPrice()
    {
        $qb = $this->createQueryBuilder('et');
        $query =$qb
            ->select("MIN(et.price) AS price")
            ->addSelect('e.id as ID')
            ->Join('et.event', 'e')
            ->groupBy('et.event')
            ->andWhere('e.status =:status')
            ->setParameter('status','Activate');
        $result = $query->getQuery()->getResult();

        $data = array();
        foreach($result as $row) {
            $data[$row['ID']] = $row['price'];
        }

        return $data;

    }
}