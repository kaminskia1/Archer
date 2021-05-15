<?php

namespace App\Command;

use App\Entity\Core\RegistrationCode;
use App\Entity\Core\User;
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

class ArcherAuthenticateUserCommand extends Command
{
    protected static $defaultName = 'archer:auth';

    private $entityManager;

    private $passwordEncoder;

    private const AUTH_SUCCESS = 0;

    private const AUTH_INVALID_CREDS = 1;

    private const AUTH_INVALID_HWID = 2;



    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Check a user\'s Credentials')
            ->addArgument('uuid', InputArgument::REQUIRED, 'User\'s UUID')
            ->addArgument('password', InputArgument::REQUIRED, 'User\'s password')
            ->addArgument('hwid', InputArgument::REQUIRED, 'User\'s HWID')
        ;
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $uuid = $input->getArgument('uuid');
        $password = $input->getArgument('password');
        $hwid = $input->getArgument('hwid');

        /**
         * @var User $user
         */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['uuid' => $uuid]);

        // Check that user exists
        if (!$user) {
            // fail authentication with invalid credentials
            $output->write(self::AUTH_INVALID_CREDS);
            return Command::FAILURE;
        }
        
        // Check that password is correct
        if ($this->passwordEncoder->isPasswordValid($user, $password))
        {
            $output->write(self::AUTH_INVALID_CREDS);
            return Command::FAILURE;
        }


        if ($user->getHwid() == null)
        {
            $user->setHwid($hwid);
        }

        if ($user->getHwid() != $hwid)
        {
            $output->write(self::AUTH_INVALID_HWID);
            return Command::FAILURE;
        }


        $output->write(self::AUTH_SUCCESS);
        return Command::SUCCESS;
    }
}
