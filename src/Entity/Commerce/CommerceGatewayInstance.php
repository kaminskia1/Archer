<?php

namespace App\Entity\Commerce;

use App\Model\CommerceTraitModel;
use App\Repository\Commerce\CommerceGatwayInstanceRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CommerceGatwayInstanceRepository::class)
 */
class CommerceGatewayInstance
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


    /**
     * Convert this entity to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName() ?? $this->getId() ?? "GatewayInstance";
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
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
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
     * Get commerce gateway type
     *
     * @return CommerceGatewayType|null
     */
    public function getCommerceGatewayType(): ?CommerceGatewayType
    {
        return $this->commerceGatewayType;
    }

    /**
     * Set commerce gateway type
     *
     * @param CommerceGatewayType|null $commerceGatewayType
     * @return $this
     */
    public function setCommerceGatewayType(?CommerceGatewayType $commerceGatewayType): self
    {
        $this->commerceGatewayType = $commerceGatewayType;

        return $this;
    }

    /**
     * Get commerce gateway type settings
     *
     * @return mixed
     */
    public function getCommerceGatewayTypeSettings()
    {
        return $this->commerceGatewayTypeSettings;
    }

    /**
     * Set commerce gateway type settings
     *
     * @param $commerceGatewayTypeSettings
     * @return $this
     */
    public function setCommerceGatewayTypeSettings($commerceGatewayTypeSettings): self
    {
        $this->commerceGatewayTypeSettings = $commerceGatewayTypeSettings;

        return $this;
    }

    /**
     * Get active state
     *
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * Set active state
     *
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

}
