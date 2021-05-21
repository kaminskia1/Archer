<?php

namespace App\Command;

use App\Entity\Core\CoreUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ArcherInfractUserCommand
 *
 * @package App\Command
 */
class ArcherInfractUserCommand extends Command
{

    /**
     * @var string Command name
     */
    protected static $defaultName = 'archer:infract';

    /**
     * @var EntityManagerInterface Entity Manager instance
     */
    private $entityManager;

    /**
     * Command constructor
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        // Import and load EntityManager and PasswordEncoder
        $this->entityManager = $entityManager;

        // Run parent command constructor
        parent::__construct();
    }

    /**
     * Command Configuration
     */
    protected function configure()
    {
        $this
            ->setDescription('Check a user\'s Credentials')
            ->addArgument('uuid', InputArgument::REQUIRED, 'User\'s UUID')
            ->addArgument('type', InputArgument::REQUIRED, 'Infraction type')
            ->addArgument('points', InputArgument::REQUIRED, 'Points to add');
    }

    /**
     * Command execution
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Grab uuid, password, and hardware identifier from input arguments
        $uuid = $input->getArgument('uuid');
        $type = $input->getArgument('type');
        $points = $input->getArgument('points');

        $user = $this->entityManager->getRepository(CoreUser::class)->findOneBy(['uuid' => $uuid]);

        // Check if user exists
        if (!$user)
        {
            // Invalid user provided
            return Command::FAILURE;
        }

        // Add type and points
        $user->addInfractionType((int)$type);
        $user->addInfractionPoints((int)$points);

        // Save user
        $this->entityManager->flush();

        // Return success
        return Command::SUCCESS;
    }

}