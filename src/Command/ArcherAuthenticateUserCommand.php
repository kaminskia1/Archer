<?php

namespace App\Command;

use App\Entity\Core\CoreUser;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ArcherAuthenticateUserCommand
 *
 * @package App\Command
 */
class ArcherAuthenticateUserCommand extends Command
{

    /**
     * @var string Command name
     */
    protected static $defaultName = 'archer:auth';
    /**
     * @var EntityManagerInterface Entity Manager
     */
    private $entityManager;
    /**
     * @var UserPasswordEncoderInterface Password Encoder
     */
    private $passwordEncoder;


    /**
     * @const Authentication Successful code
     */
    private const AUTH_SUCCESS = 1;
    /**
     * @const Authentication unsuccessful due to invalid username/password
     */
    private const AUTH_FAIL_INVALID_CREDS = 2;
    /**
     * @const Authentication unsuccessful due to invalid HWID provided
     */
    private const AUTH_FAIL_INVALID_HWID = 3;
    /**
     * @const Authentication unsuccessful due to exceeding max infractions
     */
    private const AUTH_FAIL_EXCEEDING_INFRACTIONS = 4;
    /**
     * @const Authentication unsuccessful due to nonexistant subscrition
     */
    private const AUTH_FAIL_SUBSCRIPTION_NOT_FOUND = 5;
    /**
     * @Const Authentication unsuccessful due to expired subscription
     */
    private const AUTH_FAIL_SUBSCRIPTION_EXPIRED = 6;


    /**
     * Command constructor
     *
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        // Import and load EntityManager and PasswordEncoder
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;

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
            ->addArgument('password', InputArgument::REQUIRED, 'User\'s password')
            ->addArgument('hwid', InputArgument::REQUIRED, 'User\'s HWID')
            ->addArgument('package', InputArgument::OPTIONAL, 'Package ID to check');
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
        $password = $input->getArgument('password');
        $hwid = $input->getArgument('hwid');
        $package = $input->getArgument('package') ?? null;

        /**
         * Grab the provided user object
         *
         * @var CoreUser $user
         */
        $user = $this->entityManager->getRepository(CoreUser::class)->findOneBy(['uuid' => $uuid]);

        // Check that user exists
        if (!$user) {
            // fail authentication with invalid credentials
            return self::AUTH_FAIL_INVALID_CREDS;
        }

        // Check that password is correct
        if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            // fail authentication with invalid credentials
            return self::AUTH_FAIL_INVALID_CREDS;
        }

        // Check that hwid exists
        if ($user->getHwid() == null) {
            $user->setHwid($hwid);
        }

        // Check if hwid same as provided
        if ($user->getHwid() != $hwid) {
            return self::AUTH_FAIL_INVALID_HWID;
        }

        if ($user->getInfractionPoints() >= 500) {
            return self::AUTH_FAIL_EXCEEDING_INFRACTIONS;
        }

        // Save the user, in the event that the hardware identifier was updated
        $this->entityManager->flush();

        if ($package == null)
        {
            return self::AUTH_SUCCESS;
        }

        foreach ($user->getCommerceUserSubscriptions() as $sub) {
            if ($sub->getId() == $package) {
                if ($sub->getExpiryDateTime() > new DateTime('now')) {
                    // Output command success, all checks met
                    return self::AUTH_SUCCESS;
                }
                return self::AUTH_FAIL_SUBSCRIPTION_EXPIRED;
            }
        }

        return self::AUTH_FAIL_SUBSCRIPTION_NOT_FOUND;
    }

}
