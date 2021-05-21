<?php

namespace App\Model;

/**
 * Define module dependencies
 */
use App\Entity\Core\CoreModule;

/**
 * Trait CommerceTraitModel
 * This trait should be included on every sub-file of the commerce package.
 * @package App\Model
 */
trait CommerceTraitModel
{

    /**
     * Get the entity's base installation module
     *
     * @return string
     */
    public function getBaseModule(): string
    {
        return 'Commerce';
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