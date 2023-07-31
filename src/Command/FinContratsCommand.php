<?php

namespace App\Command;

use App\Entity\User;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'app:fin-contrats',
    description: 'Efface les données dont la date de sortie est inférieure à la date du jour',
)]
class FinContratsCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Récupéreration la date du jour
        $dateDuJour = new \DateTime();

        // Suppression des données
        $repository = $this->entityManager->getRepository(User::class);
        $donneesASupprimer = $repository->createQueryBuilder('e')
            ->where('e.datesortie < :dateDuJour')
            ->setParameter('dateDuJour', $dateDuJour)
            ->getQuery()
            ->getResult();

        // Supprimer les données une par une
        foreach ($donneesASupprimer as $donnee) {
            $this->entityManager->remove($donnee);
        }
        $this->entityManager->flush();

        $io->success('Les données ont été supprimées avec succès !');

        return Command::SUCCESS;
    }
}

