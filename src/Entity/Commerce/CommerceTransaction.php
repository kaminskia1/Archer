<?php

namespace App\Entity\Commerce;

use App\Entity\Core\CoreUser;
use App\Model\CommerceTraitModel;
use App\Repository\Commerce\CommerceTransactionRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CommerceTransactionRepository::class)
 */
class CommerceTransaction
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
     * @ORM\Column(type="datetime")
     */
    private $creationDateTime;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=\App\Entity\Commerce\CommerceGatewayInstance::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $commerceGatewayInstance;

    /**
     * @ORM\OneToOne(targetEntity=\App\Entity\Commerce\CommerceInvoice::class, cascade={"persist", "remove"})
     * @ORM\OneToOne(targetEntity=\App\Entity\Commerce\CommerceInvoice::class, cascade={"persist", "remove"})
     */
    private $commerceInvoice;

    /**
     * @ORM\OneToOne(targetEntity=\App\Entity\Commerce\CommercePurchase::class, cascade={"persist", "remove"})
     */
    private $commercePurchase;

    /**
     * @ORM\ManyToOne(targetEntity=\App\Entity\Core\CoreUser::class, inversedBy="commerceTransactions")
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


    /**
     * CommerceTransaction constructor.
     *
     * @param CommerceInvoice|null $invoice
     * @param CommercePurchase|null $purchase
     */
    public function __construct(?CommerceInvoice $invoice = null, ?CommercePurchase $purchase = null)
    {
        if ($invoice instanceof CommerceInvoice) {
            $this->setCommerceInvoice($invoice);
            $this->setAmount($invoice->getPrice());
            $this->setCommerceGatewayInstance($invoice->getCommerceGatewayInstance());
            $this->setUser($invoice->getUser());
            $this->setGatewayData($invoice->getGatewayData());
        }

        if ($purchase instanceof CommercePurchase) {
            $this->setCommercePurchase($purchase);
        }

        $this->setCreationDateTime(new DateTime());
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
     * Get creation date time
     *
     * @return DateTimeInterface|null
     */
    public function getCreationDateTime(): ?DateTimeInterface
    {
        return $this->creationDateTime;
    }

    /**
     * Set creation date time
     *
     * @param DateTimeInterface $creationDateTime
     * @return $this
     */
    public function setCreationDateTime(DateTimeInterface $creationDateTime): self
    {
        $this->creationDateTime = $creationDateTime;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float|null
     */
    public function getAmount(): ?float
    {
        return $this->amount;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return $this
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

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
     * Get commerce purchase
     *
     * @return CommercePurchase|null
     */
    public function getCommercePurchase(): ?CommercePurchase
    {
        return $this->commercePurchase;
    }

    /**
     * Set commerce purchase
     *
     * @param CommercePurchase|null $commercePurchase
     * @return $this
     */
    public function setCommercePurchase(?CommercePurchase $commercePurchase): self
    {
        $this->commercePurchase = $commercePurchase;

        return $this;
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
     * Get gateway data
     *
     * @return mixed
     */
    public function getGatewayData()
    {
        return $this->gatewayData;
    }

    /**
     * Set gateway data
     *
     * @param $gatewayData
     * @return $this
     */
    public function setGatewayData($gatewayData): self
    {
        $this->gatewayData = $gatewayData;

        return $this;
    }

}
