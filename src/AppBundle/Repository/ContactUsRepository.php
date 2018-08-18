<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ContactUsRepository extends EntityRepository
{
    public function findLastMessageNoTreated()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isTreated = false' )
            ->orderBy('c.date', 'DESC' )
            ->setMaxResults(7)
            ->getQuery()
            ->getResult();
    }
}
