<?php

namespace App\Model;

/**
 * Define module dependencies
 */
use App\Entity\Core\CoreModule;

/**
 * Trait IRCTraitModel
 * This trait should be included on every sub-file of the irc package.
 * @package App\Model
 */
trait IRCTraitModel
{

    /**
     * Get the entity's base installation module
     *
     * @return string
     */
    public function getBaseModule(): string
    {
        return 'IRC';
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