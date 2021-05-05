<?php

namespace App\Entity\Commerce;

use App\Repository\Commerce\CommerceTransactionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommerceTransactionRepository::class)
 */
class CommerceTransaction
{

    public function __construct( ?CommerceInvoice $invoice, ?CommercePurchase $purchase )
    {
        if ( $invoice instanceof CommerceInvoice )
        {
            $this->setCommerceInvoice( $invoice );
            $this->setAmount( $invoice->getPrice() );
            $this->setCommerceGatewayInstance( $invoice->getCommerceGatewayInstance() );
            $this->setUser( $invoice->getUser() );
            $this->setGatewayData( $invoice->getGatewayData() );
        }

        if ( $purchase instanceof CommercePurchase )
        {
            $this->setCommercePurchase( $purchase );
        }

        $this->setCreationDateTime( new \DateTime() );
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDateTime;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\Commerce\CommerceGatewayInstance::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $commerceGatewayInstance;

    /**
     * @ORM\OneToOne(targetEntity=App\Entity\Commerce\CommerceInvoice::class, cascade={"persist", "remove"})
     * @ORM\OneToOne(targetEntity=App\Entity\Commerce\CommerceInvoice::class, cascade={"persist", "remove"})
     */
    private $commerceInvoice;

    /**
     * @ORM\OneToOne(targetEntity=App\Entity\Commerce\CommercePurchase::class, cascade={"persist", "remove"})
     */
    private $commercePurchase;

    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\Core\User::class, inversedBy="commerceTransactions")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $staffMessage;

    /**
     * @ORM\Column(type="object", nullable=true)
     */
    private $gatewayData;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDateTime(): ?\DateTimeInterface
    {
        return $this->creationDateTime;
    }

    public function setCreationDateTime(\DateTimeInterface $creationDateTime): self
    {
        $this->creationDateTime = $creationDateTime;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

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

    public function getCommercePurchase(): ?CommercePurchase
    {
        return $this->commercePurchase;
    }

    public function setCommercePurchase(?CommercePurchase $commercePurchase): self
    {
        $this->commercePurchase = $commercePurchase;
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

    public function getStaffMessage(): ?string
    {
        return $this->staffMessage;
    }

    public function setStaffMessage(?string $staffMessage): self
    {
        $this->staffMessage = $staffMessage;

        return $this;
    }

    public function getGatewayData()
    {
        return $this->gatewayData;
    }

    public function setGatewayData($gatewayData): self
    {
        $this->gatewayData = $gatewayData;

        return $this;
    }
}
