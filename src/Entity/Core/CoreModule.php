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
 *  Controllers
 *  => Controller/{ModuleName}/{ModuleName}{ControllerName}Controller
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getCustomEntities(): ?array
    {
        return $this->customEntities;
    }

    public function setCustomEntities(array $customEntities): self
    {
        $this->customEntities = $customEntities;

        return $this;
    }

    public function getCustomControllers(): ?array
    {
        return $this->customControllers;
    }

    public function setCustomControllers(array $customControllers): self
    {
        $this->customControllers = $customControllers;

        return $this;
    }
}
