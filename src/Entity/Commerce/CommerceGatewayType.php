<?php

namespace App\Entity\Commerce;

use App\Model\CommerceTraitModel;
use App\Repository\Commerce\CommerceGatewayTypeRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CommerceGatewayTypeRepository::class)
 */
class CommerceGatewayType
{

    /**
     * Import the base module
     */
    use CommerceTraitModel;


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
     * @ORM\Column(type="string", length=255)
     */
    private $class;


    /**
     * Convert this entity to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->name ?? $this->id ?? "GatewayType";
    }

    /**
     * Get class instance
     *
     * @return mixed
     */
    public function getClassInstance()
    {
        return new $this->class();
    }


    /**
     * Get id
     *
     * @return int|null
     */
    public function getId(): ?int
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
     * Get class
     *
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * Set class
     *
     * @param string $class
     * @return $this
     */
    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

}
