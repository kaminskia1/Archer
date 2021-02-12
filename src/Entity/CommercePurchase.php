<?php

namespace App\Entity;

use App\Repository\CommercePurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommercePurchaseRepository::class)
 */
class CommercePurchase
{

    public function __construct( ?CommerceInvoice $invoice )
    {
        if ( $invoice instanceof CommerceInvoice )
        {
            $this->setCommerceInvoice( $invoice );
            $this->setUser( $invoice->getUser() );
            $this->setCommerceGatewayInstance( $invoice->getCommerceGatewayInstance() );
            $this->setAmountPaid( $invoice->getPrice() );
            $this->setCommercePackage( $invoice->getCommercePackage() );
            $this->setDuration( $invoice->getDurationDateInterval() );
        }
        $this->setCreatedOn(new \DateTime());
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CommercePackage::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $commercePackage;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="CommercePurchases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="float")
     */
    private $amountPaid;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $staffMessage;

    /**
     * @ORM\ManyToOne(targetEntity=CommerceGatewayInstance::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $commerceGatewayInstance;

    /**
     * @ORM\OneToOne(targetEntity=CommerceInvoice::class, cascade={"persist", "remove"})
     */
    private $commerceInvoice;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    public function __toString()
    {
        return $this->getId() . " (User ID: " . $this->getUser()->getId() . ")";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommercePackage(): ?CommercePackage
    {
        return $this->commercePackage;
    }

    public function setCommercePackage(?CommercePackage $commercePackage): self
    {
        $this->commercePackage = $commercePackage;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAmountPaid(): ?float
    {
        return $this->amountPaid;
    }

    public function setAmountPaid(float $amountPaid): self
    {
        $this->amountPaid = $amountPaid;

        return $this;
    }

    public function getDuration(): ?\DateInterval
    {
        return $this->duration;
    }

    public function setDuration(\DateInterval $duration): self
    {
        $this->duration = $duration;

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

    public function getCommerceGatewayInstance(): ?CommerceGatewayInstance
    {
        return $this->commerceGatewayInstance;
    }

    public function setCommerceGatewayInstance(?CommerceGatewayInstance $commerceGatewayInstance): self
    {
        $this->commerceGatewayInstance = $commerceGatewayInstance;

        return $this;
    }

    public function getCommerceInvoice(): ?CommerceInvoice
    {
        return $this->commerceInvoice;
    }

    public function setCommerceInvoice(?CommerceInvoice $commerceInvoice): self
    {
        $this->commerceInvoice = $commerceInvoice;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    public function setCreatedOn(\DateTimeInterface $createdOn): self
    {
        $this->createdOn = $createdOn;

        return $this;
    }
}
