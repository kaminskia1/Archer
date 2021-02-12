<?php

namespace App\Command;

use App\Entity\RegistrationCode;
use App\Entity\User;
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

class ArcherCreateUserCommand extends Command
{
    protected static $defaultName = 'archer:create-user';

    private $entityManager;

    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Create a new user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $password = $io->ask("Enter a password", "password");
        $nickname = $io->ask("Enter a nickname (optional)");
        $extraRole = $io->ask("Add a custom role (optional)");

        // create reg code
        $code = new RegistrationCode();
        $code->populateCode();
        $code->setEnabled(false);
        $code->setUsageDate(new DateTime);

        // create user
        $user = new User();
        $user->setRegistrationCode($code);
        $user->setNickname($nickname);
        $user->setRoles(array_merge($user->getRoles(), [$extraRole]));
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $password
            )
        );
        $user->__populate();

        $code->setUsedBy($user);

        $this->entityManager->persist($code);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success("User [" . $user->getUuid() . "] " . (strlen($user->getNickname()) > 0 ? "(" . $user->getNickname() . ") " : "") . "has been created!");


        return Command::SUCCESS;
    }
}
