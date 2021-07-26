<?php

namespace App\Command;

use App\Entity\Commerce\CommercePackage;
use App\Entity\Commerce\CommerceUserSubscription;
use App\Entity\Core\CoreUser;
use App\Entity\Logger\LoggerCommandUserSubscription;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ArcherAuthenticateUserCommand
 *
 * @package App\Command
 */
class ArcherGetUserSubscriptionCommand extends AbstractArcherCommand
{

    /**
     * @const Error: Invalid user provided
     */
    private const ERROR_INVALID_USER = 0;
    /**
     * @const Error: Invalid package provided
     */
    private const ERROR_INVALID_PACKAGE = 0;
    /**
     * @const Error: Invalid subscription provided
     */
    private const ERROR_INVALID_SUBSCRIPTION = 0;
    /**
     * @const Error: Subscription is expired
     */
    private const ERROR_SUBSCRIPTION_EXPIRED = 0;

    /**
     * @var string Command name
     */
    protected static $defaultName = 'archer:subscription';

    /**
     * @var string Log name
     */
    public $logName = 'GetSubscription';

    /**
     * @var EntityManagerInterface Entity Manager
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
            ->setDescription('Check if a user\'s subscription is valid for a certain product')
            ->addArgument('uuid', InputArgument::REQUIRED, 'User\'s UUID')
            ->addArgument('package', InputArgument::REQUIRED, 'Package to check')
            ->addArgument('debug', InputArgument::OPTIONAL, 'Show debug output');
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
        $package = $input->getArgument('package');
        $debug = $input->getArgument('debug') ?? null;
        $log = [];

        /**
         * Grab the provided user object
         *
         * @var CoreUser $user
         */
        $user = $this->entityManager->getRepository(CoreUser::class)->findOneBy(['uuid' => $uuid]);
        $log['user'] = $user;

        if (!$user) {
            $this->logSubAction(array_merge($log, [
                'response' => self::ERROR_INVALID_USER,
                'flagged' => true,
                'flagType' => "ERROR_INVALID_USER",
            ]));
            if (!is_null($debug)) $output->writeln(self::ERROR_INVALID_USER);
            return self::ERROR_INVALID_USER;
        }

        /**
         * Grab the provided package object
         *
         * @var CommercePackage $package
         */
        $package = $this->entityManager->getRepository(CommercePackage::class)->find($package);
        $log['package'] = $package;

        if (!$package) {
            $this->logSubAction(array_merge($log, [
                'response' => self::ERROR_INVALID_PACKAGE,
                'flagged' => true,
                'flagType' => "ERROR_INVALID_PACKAGE",
            ]));
            if (!is_null($debug)) $output->writeln(self::ERROR_INVALID_PACKAGE);
            return self::ERROR_INVALID_PACKAGE;
        }

        /**
         * Grab the provided subscription object
         *
         * @var CommerceUserSubscription $subscription
         */
        $subscription = $this->entityManager->getRepository(CommerceUserSubscription::class)->findOneBy(['user' => $user, 'commercePackageAssoc' => $package]);
        $log['subscription'] = $subscription;

        if (!$subscription) {
            $this->logSubAction(array_merge($log, [
                'response' => self::ERROR_INVALID_SUBSCRIPTION,
                'flagged' => true,
                'flagType' => "ERROR_INVALID_SUBSCRIPTION",
            ]));
            if (!is_null($debug)) $output->writeln(self::ERROR_INVALID_SUBSCRIPTION);
            return self::ERROR_INVALID_SUBSCRIPTION;
        }


        // Double-validation(?)
        if ($subscription->getUser()->getUuid() == $user->getUuid() && $subscription->getId() == $package->getId() && $subscription->isActive()) {

            // Grab absolute seconds between, and divide by sixty to get minutes.
            // Absolute not needed because of previous validation, but provides peace of mind
            $now = new DateTime('now');
            $minutes = (int)abs($now->getTimestamp() - $subscription->getExpiryDateTime()->getTimestamp()) / 60;
            $minutes = ($minutes < ((1 << 24) - 1)) ? $minutes : ((1 << 24) - 1);

            // Log the action
            $this->logSubAction(array_merge($log, [
                'response' => $minutes,
            ]));

            if (!is_null($debug)) $output->writeln($minutes);
            return $minutes;
        }
        $this->logSubAction(array_merge($log, [
            'response' => self::ERROR_SUBSCRIPTION_EXPIRED,
            'flagged' => true,
            'flagType' => "ERROR_SUBSCRIPTION_EXPIRED",
        ]));
        if (!is_null($debug)) $output->writeln(self::ERROR_SUBSCRIPTION_EXPIRED);
        return self::ERROR_SUBSCRIPTION_EXPIRED;
    }

    private function logSubAction(array $data)
    {
        // Ensure that logging is enabled
        if ($this->isEntityModuleEnabled()) {
            // Create and save
            $log = new LoggerCommandUserSubscription(
                $data['response'],
                $data['user'] ?? null,
                $data['package'] ?? null,
                $data['subscription'] ?? null,
                $data['flagged'] ?? false,
                $data['flagType'] ?? "",
            );

            $this->entityManager->persist($log);
            $this->entityManager->flush();
        }
    }
}
