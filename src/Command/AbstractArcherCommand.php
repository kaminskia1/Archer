<?php

namespace App\Command;

use App\Entity\Logger\LoggerCommand;
use App\Model\LoggerTraitModel;
use Symfony\Component\Console\Command\Command;

/**
 * Class AbstractArcherCommand
 *
 * @package App\Command
 */
abstract class AbstractArcherCommand extends Command
{

    use LoggerTraitModel;

    /**
     * @var string Name of command to log
     */
    public $logName;

    public function __construct()
    {
        // Hack in EntityManager
        $entityManager = $GLOBALS['kernel']->getContainer()->get('doctrine.orm.entity_manager');

        // Log and save the command
        if ($this->isEntityModuleEnabled()) {
            $log = new LoggerCommand($this);
            $entityManager->persist($log);
            $entityManager->flush();
        }

        // Continue execution
        parent::__construct();
    }
}
