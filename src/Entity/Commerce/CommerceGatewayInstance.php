<?php

namespace App\Entity\Commerce;

use App\Repository\Commerce\CommerceGatwayInstanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommerceGatwayInstanceRepository::class)
 */
class CommerceGatewayInstance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=CommerceGatewayType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $commerceGatewayType;

    /**
     * @ORM\Column(type="object")
     */
    private $commerceGatewayTypeSettings;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;


    public function __toString()
    {
        return $this->getName() ?? $this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommerceGatewayType(): ?CommerceGatewayType
    {
        return $this->commerceGatewayType;
    }

    public function setCommerceGatewayType(?CommerceGatewayType $commerceGatewayType): self
    {
        $this->commerceGatewayType = $commerceGatewayType;

        return $this;
    }

    public function getCommerceGatewayTypeSettings()
    {
        return $this->commerceGatewayTypeSettings;
    }

    public function setCommerceGatewayTypeSettings($commerceGatewayTypeSettings): self
    {
        $this->commerceGatewayTypeSettings = $commerceGatewayTypeSettings;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

}
