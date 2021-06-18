<?php

namespace App\Command;

use App\Entity\Commerce\CommerceGatewayType;
use App\Entity\Core\CoreGroup;
use App\Entity\Core\CoreModule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;

/**
 * Class ArcherSetupCommand
 *
 * @package App\Command
 */
class ArcherSetupCommand extends AbstractArcherCommand
{
    /**
     * @var string Command name
     */
    protected static $defaultName = 'archer:setup';

    /**
     * @var string Log name
     */
    public $logName = 'Setup';

    /**
     * @var EntityManagerInterface Entity Manager instance
     */
    private $entityManager;


    /**
     * Command constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        // Import EntityManager
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    /**
     * Command configuration
     */
    protected function configure()
    {
        $this
            ->setDescription('Instantiate all missing modules and commerce payment gateways')
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
        if ($io->ask("Are you sure you want to run this? <error>THIS SHOULD ONLY BE RUN AT INSTALLATION</error> [Y/n]") != 'Y')
        {
            return Command::FAILURE;
        }


        /**
         * Create and save modules, core is enabled as default and cannot be modified, as all files are vital
         */

        $modules = [
            'Commerce',
            'Support',
            'IRC',
            'Linker',
            'Logger',
            'API'
        ];

        // All initial groups to register. Groups with inheritance MUST come after their inherit-ees
        $groups = [
            'ROLE_BANNED' => ['Banned', '#000000', [] ],                     // 0
            'ROLE_USER' => ['User', '#000000', [] ],                         // 1
            'ROLE_SUBSCRIBER' => ['Subscriber', '#000000', ['ROLE_USER'] ],  // 2
            'ROLE_SELLER' => ['Seller', '#000000', ['ROLE_SUBSCRIBER'] ],    // 3
            'ROLE_MODERATOR' => ['Moderator', '#000000', ['ROLE_SELLER'] ],  // 4
            'ROLE_ADMIN' => ['Admin', '#000000', ['ROLE_MODERATOR'] ],       // 5
        ];



        foreach ($groups as $internal => $val)
        {
            if ($this->entityManager->getRepository(CoreGroup::class)->findOneBy(['internalName'=>$internal]) == null)
            {
                $group = new CoreGroup();
                $group->setName($val[0]);
                $group->setColor($val[1]);
                $group->setInternalName($internal);
                $this->entityManager->persist($group);
                $output->writeln("> Added group: $internal");
            }


        }

        foreach ($groups as $internal => $val)
        {
            $group = $this->entityManager->getRepository(CoreGroup::class)->findOneBy(['internalName'=>$internal]);
            foreach ($val[2] as $v)
            {
                if ($this->entityManager->getRepository(CoreGroup::class)->findOneBy(['internalName'=>$v]) != null)
                {
                    $group->addInherit($this->entityManager->getRepository(CoreGroup::class)->findOneBy(['internalName'=>$v]));
                } else {
                    $output->writeln("# Failed to add inheritance [$v] to group [$internal] ");
                }
            }
        }

        foreach ($modules as $name)
        {
            if ($this->entityManager->getRepository(CoreModule::class)->findOneBy(['name'=>$name]) == null)
            {

                $module = new CoreModule();
                $module->setName($name);
                $module->setIsEnabled(false);
                $this->entityManager->persist($module);
                $output->writeln("> Added module: $name");
            }
        }

        /**
         * Create and save gateways that exist within the commerce module
         */
        $gateways = new Finder();
        $gateways->files()->in(__DIR__ . "\..\Module\Commerce\GatewayType");
        foreach ($gateways as $gateway) {

            // Get name
            $gatewayName = substr(
                $gateway->getRelativePathname(),
                0,
                strlen($gateway->getRelativePathname()) - 4
            );

            // If not prefixed with underscore
            if (substr($gatewayName, 0, 1) != '_')
            {
                if ($this
                        ->entityManager
                        ->getRepository(CommerceGatewayType::class)
                        ->findOneBy(['name' => $gatewayName]) == null)
                {
                    // Create new type
                    $gatewayType = new CommerceGatewayType();
                    $gatewayType->setName($gatewayName);
                    $gatewayType->setClass("App\Module\Commerce\GatewayType\\" . $gatewayName);
                    $this->entityManager->persist($gatewayType);
                    $output->writeln("> Added gateway: " . $gatewayName);
                }
            }
        }

        // Flush all changes
        $this->entityManager->flush();

        // Return success
        return Command::SUCCESS;
    }
}
