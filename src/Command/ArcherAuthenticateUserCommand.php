<?php

namespace App\Command;

use App\Entity\Commerce\CommercePackage;
use App\Entity\Commerce\CommerceUserSubscription;
use App\Entity\Core\CoreUser;
use App\Entity\Logger\LoggerCommandAuth;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ArcherAuthenticateUserCommand
 *
 * @package App\Command
 */
class ArcherAuthenticateUserCommand extends AbstractArcherCommand
{

    /**
     * @const Authentication Successful code
     */
    private const AUTH_SUCCESS = 0;
    /**
     * @const Authentication unsuccessful due to invalid username/password
     */
    private const AUTH_FAIL_INVALID_CREDS = 1;
    /**
     * @const Authentication unsuccessful due to user is banned
     */
    private const AUTH_FAIL_USER_IS_BANNED = 2;
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
     * @const Authentication unsuccessful due to expired subscription
     */
    private const AUTH_FAIL_SUBSCRIPTION_EXPIRED = 6;
    /**
     * @const Authentication unsuccessful due to nonexistant package provided
     */
    private const AUTH_FAIL_PACKAGE_NOT_FOUND = 7;
    /**
     * @const Barrier in seconds to declare a subscription 'super expired'
     */
    private const SUB_SUPER_EXPIRE_SECONDS = 604800;
    /**
     * @var string Command name
     */
    protected static $defaultName = 'archer:auth';
    /**
     * @var string Log name
     */
    public $logName = 'Auth';
    /**
     * @var EntityManagerInterface Entity Manager
     */
    private $entityManager;
    /**
     * @var UserPasswordEncoderInterface Password Encoder
     */
    private $passwordEncoder;

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
            ->addArgument('ip', InputArgument::REQUIRED, 'User\'s IP')
            ->addArgument('package', InputArgument::OPTIONAL, 'Package ID to check')
            ->addArgument("debug", InputArgument::OPTIONAL, 'Whether to print debug text or not');
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
        $uuid = $input->getArgument('uuid');               // REQUIRED
        $password = $input->getArgument('password');       // REQUIRED
        $ip = $input->getArgument('ip');                   // REQUIRED
        $hwid = $input->getArgument('hwid');               // REQUIRED
        $package = $input->getArgument('package') ?? null; // OPTIONAL
        $debug = $input->getArgument('debug') ?? null;     // OPTIONAL

        /**
         * Grab the provided user object
         *
         * @var CoreUser $user
         */
        $user = $this->entityManager->getRepository(CoreUser::class)->findOneBy(['uuid' => $uuid]);
        // Check that user exists
        if (!$user) {

            // Fail authentication with invalid credentials
            if (!is_null($debug)) $output->writeln(self::AUTH_FAIL_INVALID_CREDS);
            return self::AUTH_FAIL_INVALID_CREDS;
        }

        // Check that password is correct
        if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
            // Log the action
            $this->logAuthAction([
                'user' => $user,
                'ip' => $ip,
                'providedHwid' => $hwid,
                'response' => self::AUTH_FAIL_INVALID_CREDS
            ]);

            // Fail authentication with invalid credentials
            if (!is_null($debug)) $output->writeln(self::AUTH_FAIL_INVALID_CREDS);
            return self::AUTH_FAIL_INVALID_CREDS;
        }


        // Register the user's IP
        $user->registerLoaderIP($ip);
        $user->setLastLoaderLoginDate(new DateTime('now'));

        if ($user->isBanned()) {

            /**
             * @internal RED_FLAG => Banned user attempting access
             */
            // Log the action
            $this->logAuthAction([
                'user' => $user,
                'ip' => $ip,
                'providedHwid' => $hwid,
                'response' => self::AUTH_FAIL_USER_IS_BANNED,
                'flagged' => true,
                'flagType' => 'AUTH_FAIL_USER_IS_BANNED',
            ]);

            // Fail authentication with user is banned
            if (!is_null($debug)) $output->writeln(self::AUTH_FAIL_USER_IS_BANNED);
            return self::AUTH_FAIL_USER_IS_BANNED;
        }


        // Check that hwid exists
        if ($user->getHwid() == null) {
            $user->setHwid($hwid);
        }

        // Check if hwid same as provided
        if ($user->getHwid() != $hwid) {

            /**
             * @internal RED_FLAG => Different(?) person attempting to login
             */
            // Log the action
            $this->logAuthAction([
                'user' => $user,
                'ip' => $ip,
                'providedHwid' => $hwid,
                'response' => self::AUTH_FAIL_INVALID_HWID,
                'flagged' => true,
                'flagType' => 'AUTH_FAIL_INVALID_HWID',
            ]);

            if (!is_null($debug)) $output->writeln(self::AUTH_FAIL_INVALID_HWID);
            return self::AUTH_FAIL_INVALID_HWID;
        }

        if ($user->getInfractionPoints() >= 500) {

            /**
             * @internal RED_FLAG => Should be banned
             */
            // Log the action
            $this->logAuthAction([
                'user' => $user,
                'ip' => $ip,
                'providedHwid' => $hwid,
                'response' => self::AUTH_FAIL_EXCEEDING_INFRACTIONS,
                'flagged' => true,
                'flagType' => 'AUTH_FAIL_EXCEEDING_INFRACTIONS',
            ]);

            if (!is_null($debug)) $output->writeln(self::AUTH_FAIL_EXCEEDING_INFRACTIONS);
            return self::AUTH_FAIL_EXCEEDING_INFRACTIONS;
        }

        // Save the user, in the event that the hardware identifier was updated
        $this->entityManager->flush();

        if ($package == null) {

            // Log the action
            $this->logAuthAction([
                'user' => $user,
                'ip' => $ip,
                'providedHwid' => $hwid,
                'response' => self::AUTH_SUCCESS,
            ]);

            if (!is_null($debug)) $output->writeln(self::AUTH_SUCCESS);
            return self::AUTH_SUCCESS;
        }

        $package = $this->entityManager->getRepository(CommercePackage::class)->find((int)$package);

        if (!$package) {


            /**
             * @internal RED_FLAG => Invalid package searched
             */
            // Log the action
            $this->logAuthAction([
                'user' => $user,
                'ip' => $ip,
                'providedHwid' => $hwid,
                'response' => self::AUTH_FAIL_PACKAGE_NOT_FOUND,
                'flagged' => true,
                'flagType' => 'AUTH_FAIL_PACKAGE_NOT_FOUND',
            ]);

            // Fail authentication as package does not exist
            if (!is_null($debug)) $output->writeln(self::AUTH_FAIL_PACKAGE_NOT_FOUND);
            return self::AUTH_FAIL_PACKAGE_NOT_FOUND;
        }

        $sub = $this
            ->entityManager
            ->getRepository(CommerceUserSubscription::class)
            ->findOneBy(['user' => $user, 'commercePackageAssoc' => $package]);

        if (!$sub) {

            /**
             * @internal RED_FLAG => Attempting login without an existing subscription
             */
            // Log the action
            $this->logAuthAction([
                'user' => $user,
                'ip' => $ip,
                'providedHwid' => $hwid,
                'response' => self::AUTH_FAIL_SUBSCRIPTION_NOT_FOUND,
                'flagged' => true,
                'flagType' => 'AUTH_FAIL_SUBSCRIPTION_NOT_FOUND',
                'package' => $package
            ]);

            // Fail authentication as subscription does not exist
            if (!is_null($debug)) $output->writeln(self::AUTH_FAIL_SUBSCRIPTION_NOT_FOUND);
            return self::AUTH_FAIL_SUBSCRIPTION_NOT_FOUND;
        }


        $now = new DateTime('now');

        if ($sub->isActive()) {

            $this->logAuthAction([
                'user' => $user,
                'ip' => $ip,
                'providedHwid' => $hwid,
                'response' => self::AUTH_SUCCESS,
                'package' => $package,
                'subscriptionRemaining' => $sub->getExpiryDateTime()
            ]);

            // Output command success, all checks met
            if (!is_null($debug)) $output->writeln(self::AUTH_SUCCESS);
            return self::AUTH_SUCCESS;
        }

        // Create expired sub base data
        $logData = [
            'user' => $user,
            'ip' => $ip,
            'providedHwid' => $hwid,
            'response' => self::AUTH_FAIL_SUBSCRIPTION_EXPIRED,
            'package' => $package,
        ];

        // Grab absolute time since expiry, and check if greater than the designated point in seconds have passed since expiry
        if (((int)abs($now->getTimestamp() - $sub->getExpiryDateTime()->getTimestamp()) / 60) > self::SUB_SUPER_EXPIRE_SECONDS) {
            $logData['flagged'] = true;
            $logData['flagType'] = 'AUTH_FAIL_SUBSCRIPTION_OVER_WEEK_EXPIRED';
        }

        // Save log
        $this->logAuthAction($logData);

        // Fail with expired subscription
        if (!is_null($debug)) $output->writeln(self::AUTH_FAIL_SUBSCRIPTION_EXPIRED);
        return self::AUTH_FAIL_SUBSCRIPTION_EXPIRED;
    }


    private function logAuthAction(array $data)
    {
        // Ensure that logging is enabled
        if ($this->isEntityModuleEnabled()) {
            // Create and save
            $log = new LoggerCommandAuth(
                $data['user'],
                $data['ip'],
                $data['providedHwid'],
                $data['response'],
                $data['flagged'] ?? false,
                $data['flagType'] ?? null,
                $data['package'] ?? null,
                $data['subscriptionRemaining'] ?? null
            );

            $this->entityManager->persist($log);
            $this->entityManager->flush();
        }
    }
}
