<?php

namespace AppBundle\Command;

use AppBundle\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteAccount extends ContainerAwareCommand {

    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;

        parent::__construct();
    }

    protected function configure ()
    {

        $this->setName('FGPR:account:purge');
        $this->setDescription("Permet de supprimer un compte utilisateur 1 mois après sa désactivation");

    }

    public function execute (InputInterface $input, OutputInterface $output)
    {
        $this->userManager->purgeInactivesUsers();
        $output->writeln('Les membres inactifs de plus de 1 mois ont été supprimés de la base de données');
    }

}
