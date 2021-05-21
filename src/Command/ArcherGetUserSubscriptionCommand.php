<?php

namespace App\Command;

use App\Entity\Commerce\CommerceUserSubscription;
use App\Entity\Core\CoreUser;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ArcherAuthenticateUserCommand
 *
 * @package App\Command
 */
class ArcherGetUserSubscriptionCommand extends Command
{

    /**
     * @var string Command name
     */
    protected static $defaultName = 'archer:subscription';

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
            ->setDescription('Check a user\'s Credentials')
            ->addArgument('uuid', InputArgument::REQUIRED, 'User\'s UUID')
            ->addArgument('package', InputArgument::REQUIRED, 'Package to check');
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

        /**
         * Grab the provided user object
         *
         * @var CoreUser $user
         */
        $user = $this->entityManager->getRepository(CoreUser::class)->findOneBy(['uuid' => $uuid]);

        /**
         * Grab the provided subscription object
         *
         * @var CommerceUserSubscription $subscription
         */
        $subscription = $this->entityManager->getRepository(CommerceUserSubscription::class)->findOneBy(['user' => $user, 'id' => $package]);

        if (!$subscription) {
            return 0;
        }

        // Double-validation(?)
        if ($subscription->getUser()->getUuid() == $user->getUuid() && $subscription->getId() == $package && $subscription->isActive()) {
            $now = new DateTime('now');
            // Grab absolute seconds between, and divide by sixty to get minutes.
            // Absolute not needed because of previous validation, but provides peace of mind
            $minutes = abs($now->getTimestamp() - $subscription->getExpiryDateTime()) / 60;
            return ($minutes < ((1 << 24) - 1)) ? $minutes : ((1 << 24) - 1) ;
        }
        return 0;
    }

}
