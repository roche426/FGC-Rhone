<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ImageFoldersRepository extends EntityRepository
{
    public function sortImagesFoldersByDate()
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.creationDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
