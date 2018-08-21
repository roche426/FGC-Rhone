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

    public function findUser($id)
    {
        return $this->em->getRepository(User::class)->find($id);

    }

    public function deleteUser($id)
    {
        $user = $this->findUser($id);

        $user->setDeleteAt(new \DateTime('now'));
        $user->setIsActive(false);

        $this->em->persist($user);
        $this->em->flush();
    }

    public function keepUser($id)
    {
        $user = $this->findUser($id);

        $user->setDeleteAt(null);
        $user->setDisableAt(new \DateTime('now'));

        $this->em->persist($user);
        $this->em->flush();
    }

    public function disableUser($id)
    {
        $user = $this->findUser($id);

        $user->setDisableAt(new \DateTime('now'));
        $user->setIsActive(false);

        $this->em->persist($user);
        $this->em->flush();
    }

    public function reactiveUser($id)
    {
        $user = $this->findUser($id);

        $user->setDisableAt(null);
        $user->setIsActive(true);

        $this->em->persist($user);
        $this->em->flush();
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

                $this->em->remove($user[$k]);
                $this->em->flush();
            }
        }
    }
}
