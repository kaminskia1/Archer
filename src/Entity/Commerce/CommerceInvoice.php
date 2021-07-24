<?php

namespace App\Entity\Commerce;

use App\Entity\Core\CoreUser;
use App\Enum\Commerce\CommerceDiscountCodeTypeEnum;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use App\Model\CommerceTraitModel;
use App\Module\Commerce\GatewayType;
use App\Repository\Commerce\CommerceInvoiceRepository;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CommerceInvoiceRepository::class)
 */
class CommerceInvoice
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
     * @ORM\ManyToOne(targetEntity=\App\Entity\Core\CoreUser::class, inversedBy="CommerceInvoices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="decimal", precision=17, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="smallint")
     */
    private $paymentState;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $staffMessage;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $durationDateInterval;

    /**
     * @ORM\ManyToOne(targetEntity=\App\Entity\Commerce\CommerceDiscountCode::class)
     */
    private $discountCode;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $paidOn;

    /**
     * @ORM\ManyToOne(targetEntity=\App\Entity\Commerce\CommerceGatewayInstance::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $commerceGatewayInstance;

    /**
     * @ORM\ManyToOne(targetEntity=\App\Entity\Commerce\CommerceGatewayType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $commerceGatewayType;

    /**
     * @ORM\Column(type="string")
     */
    private $commercePackageDurationToPriceID;

    /**
     * @ORM\Column(type="object", nullable=true)
     */
    private $gatewayData;

    /**
     * @ORM\Column(type="string", length=8000, nullable=true)
     */
    private $paymentUrl;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRenewable;

    /**
     * @ORM\Column(type="decimal", precision=17, scale=2, nullable=true)
     */
    private $pricePaid;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount = 1;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $type;


    /**
     * CommerceInvoice constructor.
     */
    public function __construct()
    {
        $this->paymentState = CommerceInvoicePaymentStateEnum::INVOICE_OPEN;
        $this->isRenewable = true;
    }

    /**
     * Convert this entity to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return "Invoice " . $this->getId() ?? -1 . " (CoreUser ID: " . $this->getUser()->getId() . ")" . (!is_null($this->getPaidOn()) ? "(Paid on: " . $this->getPaidOn()->format("m/d/y h:I:s") . ")" : "");
    }


    /**
     * Approve the invoice
     *
     * @return bool
     */
    public function approve(): bool
    {

        if (($this->isOpen() || $this->isPending()) && $this->commercePackage instanceof CommercePackage)
        {

            // Begin approval flow, by issuing a pending state. Manual approval so will still approve if stock is 0
            if ($this->isOpen())
            {
                // Force the pending state
                $this->setPending(true);
            }

            // Set as paid and run all "paid" approval stuff
            $this->setPaid();

            return true;
        }

        return false;

    }

    /**
     * Begin processing the invoice (Waiting on payment, approval, etc)
     *
     * @TODO Move to the repository
     * @param bool $force
     * @return bool If all pending requirements pass, and it is successfully updated.
     */
    public function setPending($force = false): bool
    {
        if ($this->commercePackage->hasStock($this->amount) && !$force)
        {
            return false;
        }

        $this->setPaymentState(CommerceInvoicePaymentStateEnum::INVOICE_PENDING);

        $package = $this->commercePackage;
        $package->decrementStock($this->amount);

        $discount = $this->getDiscountCode();
        if ($discount != null) $discount->incrementUsage();

        // FLush to ensure package has saved, need to test whether this actually does anything
        $GLOBALS['kernel']
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->flush();

        return true;
    }

    /**
     * Set the invoice as paid
     *
     * @return bool
     */
    public function setPaid(): bool
    {
        if ($this->isPending())
        {
            $this->setPaymentState(CommerceInvoicePaymentStateEnum::INVOICE_PAID);
            $this->setPaidOn(new DateTime());
            $this->setPricePaid($this->getDiscountedPrice());

            // Create purchase and subscription
            list($purchase, $subscription) = GatewayType::createPS($this);

            // Add duration onto subscription
            $subscription->addTime($this->getDurationDateInterval());

            // Grab EntityManager from global namespace
            $entityManager = $GLOBALS['kernel']
                ->getContainer()
                ->get('doctrine.orm.entity_manager');

            // Persist all
            $entityManager->persist($purchase);
            $entityManager->persist($subscription);
            $entityManager->flush();

            return true;
        }
        return false;
    }


    /**
     * Get price with discount code applied
     *
     * @return float|null
     */
    public function getDiscountedPrice(): ?float
    {
        // Check that isset and valid
        if ($this->getDiscountCode() != null && $this->getDiscountCode()->isValid())
        {
            // Return based on type
            if ($this->getDiscountCode()->getType() == CommerceDiscountCodeTypeEnum::TYPE_PERCENTAGE)
            {
                return $this->price * (1 - $this->getDiscountCode()->getAmount());
            }
            elseif ($this->getDiscountCode()->getType() == CommerceDiscountCodeTypeEnum::TYPE_AMOUNT)
            {
                return $this->price - $this->getDiscountCode()->getAmount() >= 0 ? $this->price - $this->getDiscountCode()->getAmount() : 0;
            }
        }
        return $this->price;
    }

    /**
     * Get the discounted price in string form
     *
     * @return string|null
     */
    public function getPrettyDiscountedPrice(): ?string
    {
        return $this->getDiscountedPrice()  . " " . $_ENV['COMMERCE_CURRENCY'];
    }

    /**
     * Cancel the invoice
     *
     * @return void
     */
    public function cancel(): void
    {
        $this->setPaymentState(CommerceInvoicePaymentStateEnum::INVOICE_CANCELLED);
    }

    /**
     * Get if the invoice is open
     *
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->paymentState == CommerceInvoicePaymentStateEnum::INVOICE_OPEN;
    }

    /**
     * Get if the invoice is waiting
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->paymentState == CommerceInvoicePaymentStateEnum::INVOICE_PENDING;
    }

    /**
     * Get if the invoice is paid
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->paymentState == CommerceInvoicePaymentStateEnum::INVOICE_PAID;
    }

    /**
     * Get if the invoice is expired
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->paymentState == CommerceInvoicePaymentStateEnum::INVOICE_EXPIRED;
    }

    /**
     * Get if the invoice is cancelled
     *
     * @return bool
     */
    public function isCanceled(): bool
    {
        return $this->paymentState == CommerceInvoicePaymentStateEnum::INVOICE_CANCELLED;
    }

    /**
     * Get pretty price
     *
     * @return string
     */
    public function getPrettyPrice(): string
    {
        return $this->price . " " . $_ENV['COMMERCE_CURRENCY'];
    }


    /**
     * Return the inverse of the commerce invoice payment state enum
     *
     * @return string
     */
    public function getPrettyPaymentState(): string
    {
        switch ($this->paymentState)
        {
            case 1:
                return 'Open';
            case 2:
                return 'Paid';
            case 3:
                return 'Cancelled';
            case 4:
                return 'Expired';
            case 5:
                return 'Pending';
            default:
                return 'Error';
        }

    }

    /**
     * Get id
     *
     * @return int
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
     * Get paid on
     *
     * @return DateTimeInterface|null
     */
    public function getPaidOn(): ?DateTimeInterface
    {
        return $this->paidOn;
    }

    /**
     * Set paid on
     *
     * @param DateTimeInterface|null $paidOn
     * @return $this
     */
    public function setPaidOn(?DateTimeInterface $paidOn): self
    {
        $this->paidOn = $paidOn;

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
     * Get price
     *
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price paid
     *
     * @return float|null
     */
    public function getPricePaid(): ?float
    {
        return $this->pricePaid;
    }

    public function getPrettyPricePaid(): ?string
    {
        return $this->pricePaid  . " " . $_ENV['COMMERCE_CURRENCY'];
    }

    /**
     * Set price paid
     *
     * @param float $pricePaid
     * @return $this
     */
    public function setPricePaid(float $pricePaid): self
    {
        $this->pricePaid = $pricePaid;

        return $this;
    }

    /**
     * Get payment state
     *
     * @return int|null
     */
    public function getPaymentState(): ?int
    {
        return $this->paymentState;
    }

    /**
     * Set payment state
     *
     * @internal This should not be used outside scope, but instead by calling approve, setPending, setPaid, etc
     * @param int $paymentState
     * @return $this
     */
    public function setPaymentState(int $paymentState): self
    {
        $this->paymentState = $paymentState;

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
     * Get duration date interval
     *
     * @return DateInterval|null
     */
    public function getDurationDateInterval(): ?DateInterval
    {
        return $this->durationDateInterval;
    }

    /**
     * Set duration date interval
     *
     * @param DateInterval $durationDateInterval
     * @return $this
     */
    public function setDurationDateInterval(DateInterval $durationDateInterval): self
    {
        $this->durationDateInterval = $durationDateInterval;

        return $this;
    }

    /**
     * Get discount code
     *
     * @return CommerceDiscountCode|null
     */
    public function getDiscountCode(): ?CommerceDiscountCode
    {
        return $this->discountCode;
    }

    /**
     * Set discount code
     *
     * @param CommerceDiscountCode|null $discountCode
     * @return $this
     */
    public function setDiscountCode(?CommerceDiscountCode $discountCode): self
    {
        $this->discountCode = $discountCode;

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
     * Get commerce package duration to price
     *
     * @return int|null
     */
    public function getCommercePackageDurationToPriceID(): ?string
    {
        return $this->commercePackageDurationToPriceID;
    }

    /**
     * Set commerce packge duration to price
     *
     * @param string $commercePackageDurationToPriceID
     * @return $this
     */
    public function setCommercePackageDurationToPriceID(string $commercePackageDurationToPriceID): self
    {
        $this->commercePackageDurationToPriceID = $commercePackageDurationToPriceID;

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

    public function getPaymentUrl(): ?string
    {
        return $this->paymentUrl;
    }

    public function setPaymentUrl(?string $paymentUrl): self
    {
        $this->paymentUrl = $paymentUrl;

        return $this;
    }

    public function getIsRenewable(): ?bool
    {
        return $this->isRenewable;
    }

    public function setIsRenewable(bool $isRenewable): self
    {
        $this->isRenewable = $isRenewable;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

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
