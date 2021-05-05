<?php

namespace App\Entity\Commerce;

use App\Repository\Commerce\CommercePackageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommercePackageRepository::class)
 */
class CommercePackage
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
     * @ORM\Column(type="json")
     */
    private $durationToPrice = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stock;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVisible;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $packageUserRole;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageURI;

    /**
     * @ORM\OneToMany(targetEntity=App\Entity\Commerce\CommerceUserSubscription::class, mappedBy="CommercePackageAssoc")
     */
    private $commerceUserSubscriptions;

    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\Commerce\CommercePackageGroup::class, inversedBy="CommercePackage")
     */
    private $CommercePackageGroup;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $staffMessage;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $customJSON = [];

    /**
     * @ORM\Column(type="string", length=8000, nullable=true)
     */
    private $storeDescription;

    public function __construct()
    {
        $this->commerceUserSubscriptions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function __getLowestPriceString(): string
    {
        // please create custom object field and change this in the future so it isn't shitty like it is now (0 => 12:23.99) to (12 => 23.99)

        $tmp = (array)$this->getDurationToPrice();
        if (count($tmp) == 1)
        {
            return (string)( explode(":", reset($tmp) )[1] ?? "Error" ) . " " . $_ENV['COMMERCE_CURRENCY'];
        }

        for ($i=0;$i<count($tmp);$i++)
            $tmp[$i] = explode(":", $tmp[$i])[1] ?? "Error";

        return "From: " . ( @min($tmp) ?? "Error") . " " . $_ENV['COMMERCE_CURRENCY'];
    }

    public function __getFormattedDurationToPrice()
    {
        $tmp = $this->getDurationToPrice();
        for ($i=0;$i<count($tmp);$i++)
        {
            $l = explode(":", $tmp[$i]);
            $tmp[$i] = " $l[0] Days ($l[1] " . $_ENV['COMMERCE_CURRENCY'] . ")";
        }
        return array_flip($tmp);
    }

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

    public function getDurationToPrice(): ?array
    {
        return $this->durationToPrice;
    }

    public function setDurationToPrice(array $durationToPrice): self
    {
        $this->durationToPrice = $durationToPrice;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

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

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    public function getPackageUserRole(): ?string
    {
        return $this->packageUserRole;
    }

    public function setPackageUserRole(?string $packageUserRole): self
    {
        $this->packageUserRole = $packageUserRole;

        return $this;
    }

    public function getImageURI(): ?string
    {
        return $this->imageURI;
    }

    public function setImageURI(?string $imageURI): self
    {
        $this->imageURI = $imageURI;

        return $this;
    }

    public function getStaffMessage(): ?string
    {
        return $this->staffMessage;
    }

    public function setStaffMessage(?string $staffMessage): self
    {
        $this->staffMessage = $staffMessage;

        return $this;
    }

    public function getCustomJSON(): ?array
    {
        return $this->customJSON;
    }

    public function setCustomJSON(?array $customJSON): self
    {
        $this->customJSON = $customJSON;

        return $this;
    }

    /**
     * @return Collection|CommerceUserSubscription[]
     */
    public function getCommerceUserSubscriptions(): Collection
    {
        return $this->commerceUserSubscriptions;
    }

    public function addCommerceUserSubscription(CommerceUserSubscription $commerceUserSubscription): self
    {
        if (!$this->commerceUserSubscriptions->contains($commerceUserSubscription)) {
            $this->commerceUserSubscriptions[] = $commerceUserSubscription;
            $commerceUserSubscription->setCommercePackageAssoc($this);
        }

        return $this;
    }

    public function removeCommerceUserSubscription(CommerceUserSubscription $commerceUserSubscription): self
    {
        if ($this->commerceUserSubscriptions->removeElement($commerceUserSubscription)) {
            // set the owning side to null (unless already changed)
            if ($commerceUserSubscription->getCommercePackageAssoc() === $this) {
                $commerceUserSubscription->setCommercePackageAssoc(null);
            }
        }

        return $this;
    }

    public function getCommercePackageGroup(): ?CommercePackageGroup
    {
        return $this->CommercePackageGroup;
    }

    public function setCommercePackageGroup(?CommercePackageGroup $CommercePackageGroup): self
    {
        $this->CommercePackageGroup = $CommercePackageGroup;

        return $this;
    }

    public function getStoreDescription(): ?string
    {
        return $this->storeDescription;
    }

    public function setStoreDescription(?string $storeDescription): self
    {
        $this->storeDescription = $storeDescription;

        return $this;
    }

}
