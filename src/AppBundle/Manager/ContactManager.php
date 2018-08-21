<?php

namespace AppBundle\Manager;

use AppBundle\Entity\ContactUs;
use Doctrine\ORM\EntityManagerInterface;

class ContactManager
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function purgeTreatedMessagesUsers()
    {
        $messages = $this->em->getRepository(ContactUs::class)->findTreatedMessages();
        $dateNow = new \DateTime('now');

        foreach ($messages as $k => $value) {

            $deletionDate = $value->isTreated();

            $interval = $deletionDate->diff($dateNow);
            $monthInterval = $interval->format('%m');

            if ($monthInterval) {

                $this->em->remove($messages[$k]);
                $this->em->flush();
            }
        }
    }
}
