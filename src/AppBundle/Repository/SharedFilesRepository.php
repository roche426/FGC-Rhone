<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SharedFilesRepository extends EntityRepository
{
    public function sortFilesByDate()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.dateUpload', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function filterFiles($search)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('
                s.nameFile LIKE :search OR
                s.description LIKE :search OR
                s.subject LIKE :search
                ')
            ->setParameter('search', '%'.$search.'%')
            ->getQuery()
            ->getResult();
    }
}
