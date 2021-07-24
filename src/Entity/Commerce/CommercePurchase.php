<?php

namespace App\Entity\Commerce;

use App\Entity\Core\CoreUser;
use App\Model\CommerceTraitModel;
use App\Repository\Commerce\CommercePurchaseRepository;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommercePurchaseRepository::class)
 */
class CommercePurchase
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
     * @ORM\ManyToOne(targetEntity=\App\Entity\Commerce\CommercePackage::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $commercePackage;

    /**
     * @ORM\ManyToOne(targetEntity=\App\Entity\Core\CoreUser::class, inversedBy="CommercePurchases")
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
     * @ORM\ManyToOne(targetEntity=\App\Entity\Commerce\CommerceGatewayInstance::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $commerceGatewayInstance;

    /**
     * @ORM\OneToOne(targetEntity=\App\Entity\Commerce\CommerceInvoice::class, cascade={"persist", "remove"})
     */
    private $commerceInvoice;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $type = 'd';


    /**
     * CommercePurchase constructor.
     *
     * @param CommerceInvoice|null $invoice
     */
    public function __construct(?CommerceInvoice $invoice = null)
    {
        // Check if invoice provided
        if ($invoice instanceof CommerceInvoice) {
            // Move invoice info to this entity
            $this->setCommerceInvoice($invoice);
            $this->setUser($invoice->getUser());
            $this->setCommerceGatewayInstance($invoice->getCommerceGatewayInstance());
            $this->setAmountPaid($invoice->getPrice());
            $this->setCommercePackage($invoice->getCommercePackage());
            $this->setDuration($invoice->getDurationDateInterval());
            $this->setType($invoice->getType());
        }

        // Set creation date to current
        $this->setCreatedOn(new DateTime());
    }

    /**
     * Convert this entity to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getId() . " (User ID: " . $this->getUser()->getId() . ")";
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
     * Get user
     *
     * @return CoreUser|null
     */
    public function getUser(): ?CoreUser
    {
        return $this->user;
    }

    /**
     * Set user
     *
     * @param CoreUser|null $user
     * @return $this
     */
    public function setUser(?CoreUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get commerce package
     *
     * @return CommercePackage|null
     */
    public function getCommercePackage(): ?CommercePackage
    {
        return $this->commercePackage;
    }

    /**
     * Set commerce package
     *
     * @param CommercePackage|null $commercePackage
     * @return $this
     */
    public function setCommercePackage(?CommercePackage $commercePackage): self
    {
        $this->commercePackage = $commercePackage;

        return $this;
    }

    /**
     * Get amount paid
     *
     * @return float|null
     */
    public function getAmountPaid(): ?float
    {
        return $this->amountPaid;
    }

    /**
     * Set amount paid
     *
     * @param float $amountPaid
     * @return $this
     */
    public function setAmountPaid(float $amountPaid): self
    {
        $this->amountPaid = $amountPaid;

        return $this;
    }

    /**
     * Get duration
     *
     * @return DateInterval|null
     */
    public function getDuration(): ?DateInterval
    {
        return $this->duration;
    }

    /**
     * Set duration
     *
     * @param DateInterval $duration
     * @return $this
     */
    public function setDuration(DateInterval $duration): self
    {
        $this->duration = $duration;

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
     * Get commerce gateway instance
     *
     * @return CommerceGatewayInstance|null
     */
    public function getCommerceGatewayInstance(): ?CommerceGatewayInstance
    {
        return $this->commerceGatewayInstance;
    }

    /**
     * Set commerce gateway instance
     *
     * @param CommerceGatewayInstance|null $commerceGatewayInstance
     * @return $this
     */
    public function setCommerceGatewayInstance(?CommerceGatewayInstance $commerceGatewayInstance): self
    {
        $this->commerceGatewayInstance = $commerceGatewayInstance;

        return $this;
    }

    /**
     * Get commerce invoice
     *
     * @return CommerceInvoice|null
     */
    public function getCommerceInvoice(): ?CommerceInvoice
    {
        return $this->commerceInvoice;
    }

    /**
     * Set commerce invoice
     *
     * @param CommerceInvoice|null $commerceInvoice
     * @return $this
     */
    public function setCommerceInvoice(?CommerceInvoice $commerceInvoice): self
    {
        $this->commerceInvoice = $commerceInvoice;

        return $this;
    }

    /**
     * Get created on
     *
     * @return DateTimeInterface|null
     */
    public function getCreatedOn(): ?DateTimeInterface
    {
        return $this->createdOn;
    }

    /**
     * Set created on
     *
     * @param DateTimeInterface $createdOn
     * @return $this
     */
    public function setCreatedOn(DateTimeInterface $createdOn): self
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

}
