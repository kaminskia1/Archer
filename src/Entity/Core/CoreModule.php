<?php

namespace App\Entity\Core;

use App\Repository\Core\CoreModuleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CoreModuleRepository::class)
 */
class CoreModule
{
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
