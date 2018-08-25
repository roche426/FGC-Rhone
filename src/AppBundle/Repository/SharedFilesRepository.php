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
}
