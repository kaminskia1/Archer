<?php

namespace App\Entity\Commerce;

use App\Model\CommerceTraitModel;
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
     * @ORM\OneToMany(targetEntity=\App\Entity\Commerce\CommerceUserSubscription::class, mappedBy="CommercePackageAssoc")
     */
    private $commerceUserSubscriptions;

    /**
     * @ORM\ManyToOne(targetEntity=\App\Entity\Commerce\CommercePackageGroup::class, inversedBy="CommercePackage")
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


    /**
     * CommercePackage constructor.
     */
    public function __construct()
    {
        $this->commerceUserSubscriptions = new ArrayCollection();
    }

    /**
     * Convert this entity to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get lowest price
     *
     * @return string
     */
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

    /**
     * Get formatted duration to price
     *
     * @return array
     */
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
     * Set id
     *
     * @return int|null
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
     * Get duration to price
     *
     * @return array|null
     */
    public function getDurationToPrice(): ?array
    {
        return $this->durationToPrice;
    }

    /**
     * Set duration to price
     *
     * @param array $durationToPrice
     * @return $this
     */
    public function setDurationToPrice(array $durationToPrice): self
    {
        $this->durationToPrice = $durationToPrice;

        return $this;
    }

    /**
     * Get stock
     *
     * @return int|null
     */
    public function getStock(): ?int
    {
        return $this->stock;
    }

    /**
     * Set stock
     *
     * @param int|null $stock
     * @return $this
     */
    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get enabled state
     *
     * @return bool|null
     */
    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    /**
     * Set enabled state
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
     * Get visible state
     *
     * @return bool|null
     */
    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    /**
     * Set visible state
     *
     * @param bool $isVisible
     * @return $this
     */
    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * Get package user roles
     *
     * @return string|null
     */
    public function getPackageUserRole(): ?string
    {
        return $this->packageUserRole;
    }

    /**
     * Set package user roles
     *
     * @param string|null $packageUserRole
     * @return $this
     */
    public function setPackageUserRole(?string $packageUserRole): self
    {
        $this->packageUserRole = $packageUserRole;

        return $this;
    }

    /**
     * Get image uri
     *
     * @return string|null
     */
    public function getImageURI(): ?string
    {
        return $this->imageURI;
    }

    /**
     * Set image uri
     *
     * @param string|null $imageURI
     * @return $this
     */
    public function setImageURI(?string $imageURI): self
    {
        $this->imageURI = $imageURI;

        return $this;
    }

    /**
     * Get staff message
     *
     * @return string|null
     */
    public function getStaffMessage(): ?string
    {
        return $this->staffMessage;
    }

    /**
     * Set staff message
     *
     * @param string|null $staffMessage
     * @return $this
     */
    public function setStaffMessage(?string $staffMessage): self
    {
        $this->staffMessage = $staffMessage;

        return $this;
    }

    /**
     * Get custom json
     *
     * @return array|null
     */
    public function getCustomJSON(): ?array
    {
        return $this->customJSON;
    }

    /**
     * Set custom json
     *
     * @param array|null $customJSON
     * @return $this
     */
    public function setCustomJSON(?array $customJSON): self
    {
        $this->customJSON = $customJSON;

        return $this;
    }

    /**
     * Get commerce user subscription
     *
     * @return Collection|CommerceUserSubscription[]
     */
    public function getCommerceUserSubscriptions(): Collection
    {
        return $this->commerceUserSubscriptions;
    }

    /**
     * Add commerce user subscription
     *
     * @param CommerceUserSubscription $commerceUserSubscription
     * @return $this
     */
    public function addCommerceUserSubscription(CommerceUserSubscription $commerceUserSubscription): self
    {
        if (!$this->commerceUserSubscriptions->contains($commerceUserSubscription)) {
            $this->commerceUserSubscriptions[] = $commerceUserSubscription;
            $commerceUserSubscription->setCommercePackageAssoc($this);
        }

        return $this;
    }

    /**
     * Remove commerce user subscription
     *
     * @param CommerceUserSubscription $commerceUserSubscription
     * @return $this
     */
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

    /**
     * Get commerce package group
     *
     * @return CommercePackageGroup|null
     */
    public function getCommercePackageGroup(): ?CommercePackageGroup
    {
        return $this->CommercePackageGroup;
    }

    /**
     * Set commerce package group
     *
     * @param CommercePackageGroup $CommercePackageGroup
     * @return $this
     */
    public function setCommercePackageGroup(CommercePackageGroup $CommercePackageGroup): self
    {
        $this->CommercePackageGroup = $CommercePackageGroup;

        return $this;
    }

    /**
     * Get store description
     *
     * @return string|null
     */
    public function getStoreDescription(): ?string
    {
        return $this->storeDescription;
    }

    /**
     * Set store description
     *
     * @param string|null $storeDescription
     * @return $this
     */
    public function setStoreDescription(?string $storeDescription): self
    {
        $this->storeDescription = $storeDescription;

        return $this;
    }

}
