<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ContactUsRepository extends EntityRepository
{
    public function findLastMessageNoTreated()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isTreated is NULL' )
            ->orderBy('c.date', 'DESC' )
            ->setMaxResults(7)
            ->getQuery()
            ->getResult();
    }

    public function findTreatedMessages()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isTreated is NOT NULL' )
            ->andWhere('c.isArchived = false' )
            ->getQuery()
            ->getResult();
    }
}
