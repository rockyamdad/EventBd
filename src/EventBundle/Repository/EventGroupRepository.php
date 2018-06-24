<?php

namespace EventBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EventTypeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventGroupRepository extends EntityRepository
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
}