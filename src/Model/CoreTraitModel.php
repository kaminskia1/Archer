<?php

namespace App\Model;

/**
 * Trait CoreTraitModel
 * This trait should be included on every sub-file of the core package.
 * @package App\Model
 */
trait CoreTraitModel
{
    /**
     * Get if entity's module is installed and enabled
     *
     * @return bool
     */
    public function isEntityModuleEnabled(): bool
    {
        return true;
    }

    /**
     * Get the entity's base installation module
     *
     * @return string
     */
    public function getBaseModule(): string
    {
        return 'Core';
    }

}