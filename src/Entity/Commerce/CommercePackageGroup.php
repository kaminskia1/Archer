<?php

namespace App\Entity\Commerce;

use App\Model\CommerceTraitModel;
use App\Repository\Commerce\CommercePackageGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommercePackageGroupRepository::class)
 */
class CommercePackageGroup
{

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageURI;

    /**
     * @ORM\OneToMany(targetEntity=\App\Entity\Commerce\CommercePackage::class, mappedBy="CommercePackageGroup")
     */
    private $commercePackage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $staffMessage;

    public function __construct()
    {
        $this->commercePackage = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
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

    public function getImageURI(): ?string
    {
        return $this->imageURI;
    }

    public function setImageURI(?string $imageURI): self
    {
        $this->imageURI = $imageURI;

        return $this;
    }

    /**
     * @return Collection|CommercePackage[]
     */
    public function getCommercePackage(): Collection
    {
        return $this->commercePackage;
    }

    public function addCommercePackage(CommercePackage $commercePackage): self
    {
        if (!$this->commercePackage->contains($commercePackage)) {
            $this->commercePackage[] = $commercePackage;
            $commercePackage->setCommercePackageGroup($this);
        }

        return $this;
    }

    public function removeCommercePackage(CommercePackage $commercePackage): self
    {
        if ($this->commercePackage->removeElement($commercePackage)) {
            // set the owning side to null (unless already changed)
            if ($commercePackage->getCommercePackageGroup() === $this) {
                $commercePackage->setCommercePackageGroup(null);
            }
        }

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
}
