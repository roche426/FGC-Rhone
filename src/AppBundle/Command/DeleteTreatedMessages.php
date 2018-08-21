<?php

namespace AppBundle\Command;

use AppBundle\Manager\ContactManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;

use Symfony\Component\Console\Output\OutputInterface;


class DeleteTreatedMessages extends ContainerAwareCommand
{
    private $contactManager;

    public function __construct(ContactManager $contactManager)
    {
        $this->contactManager = $contactManager;

        parent::__construct();
    }

    protected function configure ()
    {

        $this->setName('FGPR:messages:purge');
        $this->setDescription("Permet de supprimer les messages traités de plus de 30 jours");

    }

    public function execute (InputInterface $input, OutputInterface $output)
    {
        $this->contactManager->purgeTreatedMessagesUsers();
        $output->writeln('Les messages traités de plus d\'un mois ont été supprimés');
    }

}
