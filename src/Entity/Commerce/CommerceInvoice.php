<?php

namespace App\Entity\Commerce;

use App\Entity\Core\CoreUser;
use App\Model\CommerceTraitModel;
use App\Repository\Commerce\CommerceInvoiceRepository;
use DateInterval;
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
     * @ORM\Column(type="integer")
     */
    private $commercePackageDurationToPriceID;

    /**
     * @ORM\Column(type="object", nullable=true)
     */
    private $gatewayData;


    /**
     * Convert this entity to a string
     *
     * @return string
     */
    public function __toString()
    {
        return "Invoice " . $this->getId() ?? -1 . " (CoreUser ID: " . $this->getUser()->getId() . ")" . (!is_null($this->getPaidOn()) ? "(Paid on: " . $this->getPaidOn()->format("m/d/y h:I:s") . ")" : "");
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
     * Get commerce package duraiton to price
     *
     * @return int|null
     */
    public function getCommercePackageDurationToPriceID(): ?int
    {
        return $this->commercePackageDurationToPriceID;
    }

    /**
     * Set commerce packge duration to price
     *
     * @param int $commercePackageDurationToPriceID
     * @return $this
     */
    public function setCommercePackageDurationToPriceID(int $commercePackageDurationToPriceID): self
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

}
