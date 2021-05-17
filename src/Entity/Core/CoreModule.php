<?php

namespace App\Entity\Core;

use App\Model\CoreTraitModel;
use App\Repository\Core\CoreModuleRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CoreModuleRepository::class)
 *
 * Module Layout
 *
 *  - All classes must inherit their respective model, e.g. for commmerce, it would be ComerceTraitModel in src/Model
 *
 *
 *  Controller
 *  => Controller/{ModuleName}/{ModuleName}/Site/{ControllerName}Controller
 *  => Controller/{ModuleName}/{ModuleName}/Api/{ControllerName}Controller
 *  => Controller/{ModuleName}/{ModuleName}/Api/Secure/{ControllerName}Controller
 *
 *  Entity
 *  => Entity/{ModuleName}/{ModuleName}{EntityName}
 *
 *  Enum
 *  => Enum/{ModuleName}/{ModuleName}{EnumName}
 *
 *  EventListener
 *  => EventListener/{ModuleName}/{ModuleName}{EventName}Listener
 *
 *  EventSubscriber
 *  => EventSubscriber/{ModuleName}/{ModuleName}{SubscriberName}Subscriber
 *
 *  Form
 *  => Form/{ModuleName}{FormName}
 *
 *  Model
 *  => Model/{ModuleName}/{ModuleName}TraitModel
 *
 *  Module
 *  => Module/{ModuleName}/
 *
 *  Repository
 *  => Repository/{ModuleName}/{ModuleName}{EntityName}
 *
 *  Template
 *  => {modulename}/{template}
 *
 *
 */
class CoreModule
{

    /**
     * Import the base module
     */
    use CoreTraitModel;


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\Column(type="array")
     */
    private $customEntities = [];

    /**
     * @ORM\Column(type="array")
     */
    private $customControllers = [];


    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get enable state
     *
     * @return bool|null
     */
    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    /**
     * Set enable state
     *
     * @param bool $isEnabled
     * @return $this
     */
    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get custom entities
     *
     * @return array|null
     */
    public function getCustomEntities(): ?array
    {
        return $this->customEntities;
    }

    /**
     * Set custom entities
     *
     * @param array $customEntities
     * @return $this
     */
    public function setCustomEntities(array $customEntities): self
    {
        $this->customEntities = $customEntities;

        return $this;
    }

    /**
     * Get custom controllers
     *
     * @return array|null
     */
    public function getCustomControllers(): ?array
    {
        return $this->customControllers;
    }

    /**
     * Set custom controllers
     *
     * @param array $customControllers
     * @return $this
     */
    public function setCustomControllers(array $customControllers): self
    {
        $this->customControllers = $customControllers;

        return $this;
    }

}
