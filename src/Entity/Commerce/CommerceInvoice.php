<?php

namespace App\Entity\Commerce;

use App\Entity\Core\CoreUser;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use App\Model\CommerceTraitModel;
use App\Repository\Commerce\CommerceInvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommerceInvoiceRepository::class)
 */
class CommerceInvoice
{

    use CommerceTraitModel;

    public function __toString()
    {
        return "Invoice " . $this->getId() ?? -1 . " (CoreUser ID: " . $this->getUser()->getId() . ")" . (!is_null($this->getPaidOn()) ? "(Paid on: " . $this->getPaidOn()->format("m/d/y h:I:s") . ")" : "");
    }


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\Commerce\CommercePackage::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $commercePackage;

    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\Core\CoreUser::class, inversedBy="CommerceInvoices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="decimal", precision=17, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
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
     * @ORM\ManyToOne(targetEntity=App\Entity\Commerce\CommerceDiscountCode::class)
     */
    private $discountCode;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $paidOn;

    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\Commerce\CommerceGatewayInstance::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $commerceGatewayInstance;

    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\Commerce\CommerceGatewayType::class)
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

    public function getUser(): ?CoreUser
    {
        return $this->user;
    }

    public function setUser(?CoreUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPaymentState(): ?string
    {
        return $this->paymentState;
    }

    public function setPaymentState(string $paymentState): self
    {
        $this->paymentState = $paymentState;

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

    public function getDurationDateInterval(): ?\DateInterval
    {
        return $this->durationDateInterval;
    }

    public function setDurationDateInterval(\DateInterval $durationDateInterval): self
    {
        $this->durationDateInterval = $durationDateInterval;

        return $this;
    }

    public function getDiscountCode(): ?CommerceDiscountCode
    {
        return $this->discountCode;
    }

    public function setDiscountCode(?CommerceDiscountCode $discountCode): self
    {
        $this->discountCode = $discountCode;

        return $this;
    }

    public function getPaidOn(): ?\DateTimeInterface
    {
        return $this->paidOn;
    }

    public function setPaidOn(?\DateTimeInterface $paidOn): self
    {
        $this->paidOn = $paidOn;

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

    public function getCommerceGatewayType(): ?CommerceGatewayType
    {
        return $this->commerceGatewayType;
    }

    public function setCommerceGatewayType(?CommerceGatewayType $commerceGatewayType): self
    {
        $this->commerceGatewayType = $commerceGatewayType;

        return $this;
    }

    public function getCommercePackageDurationToPriceID(): ?int
    {
        return $this->commercePackageDurationToPriceID;
    }

    public function setCommercePackageDurationToPriceID(int $commercePackageDurationToPriceID): self
    {
        $this->commercePackageDurationToPriceID = $commercePackageDurationToPriceID;

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
