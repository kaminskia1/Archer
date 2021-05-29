<?php

namespace App\Command;

use App\Module\Core\CorePasswordHasher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ArcherCPHEncodeCommand
 *
 * @package App\Command
 */
class ArcherCPHEncodeCommand extends AbstractArcherCommand
{
    /**
     * @var string Command name
     */
    protected static $defaultName = 'archer:cph-encode';

    /**
     * @var string Log name
     */
    public $logName = 'Encoder';

    /**
     * Command constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Command Configuration
     */
    protected function configure()
    {
        $this
            ->setDescription('Encode a password')
            ->addArgument('password', InputArgument::OPTIONAL, 'Password to encode', '')
        ;
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
        // Create SymfonyStyle IO instance
        $io = new SymfonyStyle($input, $output);

        // Set password to buffer
        $password = $input->getArgument('password');

        // Check if password provided in command arguments
        if ($password == '')
        {
            // Prompt user if not
            $password = $io->ask("Enter a password", "password");
        }

        // Print out encoded password
        $io->success("Encoded: [ " . CorePasswordHasher::hashPassword($password) . " ]");

        // Return success
        return Command::SUCCESS;
    }
}
