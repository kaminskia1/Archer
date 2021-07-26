<?php

namespace App\Entity\Commerce;

use App\Entity\Core\CoreUser;
use App\Model\CommerceTraitModel;
use App\Repository\Commerce\CommerceLicenseKeyRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=CommerceLicenseKeyRepository::class)
 */
class CommerceLicenseKey
{

    use CommerceTraitModel;


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity=CommercePackage::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $package;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity=CommerceInvoice::class)
     */
    private $invoice;

    /**
     * @ORM\Column(type="boolean")
     * @ORM\JoinColumn(nullable=false)
     */
    private $active = true;

    /**
     * @ORM\ManyToOne(targetEntity=CoreUser::class, inversedBy="commerceLicenseKeys")
     */
    private $usedBy = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @ORM\ManyToOne(targetEntity=CoreUser::class)
     */
    private $purchasedBy;


    /**
     * CommerceLicenseKey constructor.
     *
     * Provide package and duration separate from invoice, to allow for non-bound creation
     *
     * @param CommercePackage|null $package
     * @param DateInterval|null    $duration
     * @param CommerceInvoice|null $invoice
     *
     * @throws Exception
     */
    public function __construct(CommercePackage $package = null, DateInterval $duration = null, CommerceInvoice $invoice = null)
    {
        $this->code = Uuid::v4();
        $this->package = $package;
        $this->duration = $duration;
        $this->invoice = $invoice;
        $this->createdOn = new DateTime('now');
        $this->purchasedBy = $invoice->getUser() ?? null;
    }

    public function __toString(): string
    {
        return $this->getCode();
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPackage(): ?CommercePackage
    {
        return $this->package;
    }

    public function setPackage(?CommercePackage $package): self
    {
        $this->package = $package;

        return $this;
    }

    public function getDuration(): ?DateInterval
    {
        return $this->duration;
    }

    public function setDuration(DateInterval $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getInvoice(): ?CommerceInvoice
    {
        return $this->invoice;
    }

    public function setInvoice(?CommerceInvoice $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getUsedBy(): ?CoreUser
    {
        return $this->usedBy;
    }

    public function setUsedBy(?CoreUser $usedBy): self
    {
        $this->usedBy = $usedBy;

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

    public function getPurchasedBy(): ?CoreUser
    {
        return $this->purchasedBy;
    }

    public function setPurchasedBy(?CoreUser $purchasedBy): self
    {
        $this->purchasedBy = $purchasedBy;

        return $this;
    }
}
