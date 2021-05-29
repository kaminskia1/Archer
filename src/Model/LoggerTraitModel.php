<?php

namespace App\Model;

/**
 * Define module dependencies
 */
use App\Entity\Core\CoreModule;

/**
 * Trait LoggerTraitModel
 * This trait should be included on every sub-file of the support package.
 * @package App\Model
 */
trait LoggerTraitModel
{

    /**
     * Get the entity's base installation module
     *
     * @return string
     */
    public function getBaseModule(): string
    {
        return 'Logger';
    }

    /**
     * Get if entity's module is installed and enabled
     *
     * @return bool
     */
    public function isEntityModuleEnabled(): bool
    {
        // Hack in Entity Manager
        return $GLOBALS['kernel']
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(CoreModule::class)
            ->isModuleLoaded($this->getBaseModule());
    }

}