<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function purgeInactivesUsers()
    {
        $user = $this->em->getRepository(User::class)->findDeletedUsers();
        $dateNow = new \DateTime('now');

        foreach ($user as $k => $value) {

            $deletionDate = $value->getDeleteAt();

            $interval = $deletionDate->diff($dateNow);
            $monthInterval = $interval->format('%m');

            if ($monthInterval) {
                if ($user[$k]->getPictureProfil()){
                    unlink('web/' . $user[$k]->getPictureProfil());
                }

                if ($user[$k]->getFiles()) {
                    if ($user[$k]->getFiles()->getIdCard()) {
                        unlink($user[$k]->getFiles()->getIdCard());
                    }
                }

                $comments = $user[$k]->getComments();
                $articles = $user[$k]->getArticles();

                if ($comments) {
                    foreach ($articles as $article) {
                        $article->setUser(null);
                    }
                }

                if ($articles) {
                    foreach ($comments as $comment) {
                        $comment->setUser(null);
                    }
                }

                $this->em->remove($user[$k]);
                $this->em->flush();
            }
        }
    }
}
