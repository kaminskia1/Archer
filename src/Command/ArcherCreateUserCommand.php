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
 * Class ArcherCreateUserCommand
 *
 * @package App\Command
 */
class ArcherCreateUserCommand extends AbstractArcherCommand
{
    /**
     * @var string Command name
     */
    protected static $defaultName = 'archer:create-user';

    /**
     * @var string Log name
     */
    public $logName = 'CreateUser';

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
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        // Import EntityManager and PasswordEncoder
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;

        // Call the parent command constructor
        parent::__construct();
    }

    /**
     * Command configuration
     */
    protected function configure()
    {
        $this
            ->setDescription('Create a new user')
            ->addArgument('password', InputArgument::OPTIONAL, 'The password to encode')
            ->addArgument('nickname', InputArgument::OPTIONAL, 'A Nickname to append')
            ->addArgument('customRole', InputArgument::OPTIONAL, 'A custom role to accompany ROLE_USER')
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

        // Prompt for password, nickname if applicable, and custom role to bind if applicable
        $password = $input->getArgument('password') ?? $io->ask("Enter a password", "password");
        $nickname = $input->getArgument('nickname') ?? $io->ask("Enter a nickname (optional)");
        $customRole = $input->getArgument('customRole') ?? $io->ask("Add a custom role (optional)");

        // Create registration code
        $code = new CoreRegistrationCode();

        // Populate, enable, and set usage datetime to current
        $code->populateCode();
        $code->setEnabled(false);
        $code->setUsageDate(new DateTime);

        // create user
        $user = new CoreUser();

        // Bind new user to previous registration code, set nickname and roles
        $user->setRegistrationCode($code);
        $user->setNickname($nickname);
        $user->setRoles(array_merge($user->getRoles(), [$customRole]));

        // Encode password with CorePasswordHasher, and then encode again with the server's standard encoding
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                CorePasswordHasher::hashPassword($password)
            )
        );

        // Populate remaining empty fields on the user
        $user->__populate();

        // Set the generated registration code as ised
        $code->setUsedBy($user);

        // Ensure that both the generated registration code, and new user are saved to the database
        $this->entityManager->persist($code);
        $this->entityManager->persist($user);

        // Flush the Entity Manger
        $this->entityManager->flush();

        // Output success with the user's data, as creation is complete
        $io->success("CoreUser [" . $user->getUuid() . "] " . (strlen($user->getNickname()) > 0 ? "(" . $user->getNickname() . ") " : "") . "has been created!");

        // Return successful execution
        return Command::SUCCESS;
    }
}
