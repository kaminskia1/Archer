<?php

namespace App\Command;

use App\Entity\Commerce\CommerceGatewayType;
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
class ArcherSetupCommand extends Command
{
    /**
     * @var string Command name
     */
    protected static $defaultName = 'archer:setup';

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
            ->setDescription('Reset a user\'s password')
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

        // Create commerce module
        if ($this->entityManager->getRepository(CoreModule::class)->findOneBy(['name'=>'Commerce']) == null)
        {

            $commerceModule = new CoreModule();
            $commerceModule->setName('Commerce');
            $commerceModule->setIsEnabled(false);
            $this->entityManager->persist($commerceModule);
            $output->writeln("> Added module: Commerce");
        }

        // Create support module
        if ($this->entityManager->getRepository(CoreModule::class)->findOneBy(['name'=>'Support']) == null)
        {
            $supportModule = new CoreModule();
            $supportModule->setName('Support');
            $supportModule->setIsEnabled(false);
            $this->entityManager->persist($supportModule);
            $output->writeln("> Added module: Support");
        }

        // Create IRC module
        if ($this->entityManager->getRepository(CoreModule::class)->findOneBy(['name'=>'IRC']) == null)
        {
            $IRCModule = new CoreModule();
            $IRCModule->setName('IRC');
            $IRCModule->setIsEnabled(false);
            $this->entityManager->persist($IRCModule);
            $output->writeln("> Added module: IRC");
        }

        // Create Linker module
        if ($this->entityManager->getRepository(CoreModule::class)->findOneBy(['name'=>'Linker']) == null)
        {
            $LinkerModule = new CoreModule();
            $LinkerModule->setName('Linker');
            $LinkerModule->setIsEnabled(false);
            $this->entityManager->persist($LinkerModule);
            $output->writeln("> Added module: Linker");
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
