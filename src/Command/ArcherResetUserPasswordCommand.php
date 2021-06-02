<?php

namespace App\Command;

use App\Entity\Core\CoreRegistrationCode;
use App\Entity\Core\CoreUser;
use App\Module\Core\CorePasswordHasher;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ArcherResetUserPasswordCommand
 *
 * @package App\Command
 */
class ArcherResetUserPasswordCommand extends AbstractArcherCommand
{
    /**
     * @var string Command name
     */
    protected static $defaultName = 'archer:reset-password';

    /**
     * @var string Log name
     */
    public $logName = 'ResetPassword';

    /**
     * @var EntityManagerInterface Entity Manager instance
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface Password Encoder instance
     */
    private $passwordEncoder;

    /**
     * Command constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        // Inject EntityManager and PasswordEncoder services
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;

        // Run parent command constructor
        parent::__construct();
    }

    /**
     * Command configuraiton
     */
    protected function configure()
    {
        $this
            ->setDescription('Reset a user\'s password')
            ->addArgument('uuid', InputArgument::OPTIONAL, 'UUID to reset')
            ->addArgument('password', InputArgument::OPTIONAL, 'New password to use');
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
        // Create SymfonyStyle io
        $io = new SymfonyStyle($input, $output);

        // Grab username & new password
        $uuid = $input->getArgument('uuid') ?? $io->ask("Enter a UUID");
        $password = $input->getArgument('password') ?? $io->ask("Enter a password");

        // Grab user from entitymanager
        $user = $this->entityManager->getRepository(CoreUser::class)->findOneBy(['uuid' => $uuid]);

        // Check that user exists
        if (!$user) {
            // fail authentication with invalid credentials
            $output->write("Invalid UUID");
            return Command::FAILURE;
        }

        // Set user password, encode with custom then with default
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                CorePasswordHasher::hashPassword($password)
            )
        );

        // Print success message
        $io->success("UUID [" . $user->getUuid() . "] " . (strlen($user->getNickname()) > 0 ? "(" . $user->getNickname() . ") " : "") . "has been updated!");

        // Return success
        return Command::SUCCESS;
    }

}
