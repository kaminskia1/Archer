<?php

namespace App\Entity\Commerce;

use App\Model\CommerceTraitModel;
use App\Repository\Commerce\CommerceDiscountCodeRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Uid\Uuid;


/**
 * @ORM\Entity(repositoryClass=CommerceDiscountCodeRepository::class)
 */
class CommerceDiscountCode
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
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expiryDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $currentUsage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxUsage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $staffMessage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;


    /**
     * Convert this entity to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->code . " (" . ($this->type == "TYPE_PERCENT" ? $this->amount . "%" : "$" . $this->amount) . ")";
    }

    /**
     * Populate empty fields
     *
     * @return $this
     * @throws Exception
     */
    public function populate(): self
    {
        $this->code = $this->code ?? Uuid::v4();
        $this->currentUsage = $this->currentUsage ?? 0;
        $this->maxUsage = $this->maxUsage ?? -1;
        $this->staffMessage = $this->staffMessage ?? "";
        $this->isEnabled = $this->isEnabled ?? false;
        $this->expiryDate = $this->expiryDate ?? new DateTime(2038 - 1 - 1);

        return $this;
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
     * Get code
     *
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get type
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

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
     * Get expiry date
     *
     * @return DateTimeInterface|null
     */
    public function getExpiryDate(): ?DateTimeInterface
    {
        return $this->expiryDate;
    }

    /**
     * Set expiry date
     *
     * @param DateTimeInterface|null $expiryDate
     * @return $this
     */
    public function setExpiryDate(?DateTimeInterface $expiryDate): self
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get current usage
     *
     * @return int|null
     */
    public function getCurrentUsage(): ?int
    {
        return $this->currentUsage;
    }

    /**
     * Set current usage
     *
     * @param int $currentUsage
     * @return $this
     */
    public function setCurrentUsage(int $currentUsage): self
    {
        $this->currentUsage = $currentUsage;

        return $this;
    }

    /**
     * Get max usage
     *
     * @return int|null
     */
    public function getMaxUsage(): ?int
    {
        return $this->maxUsage;
    }

    /**
     * Set max usage
     *
     * @param int|null $maxUsage
     * @return $this
     */
    public function setMaxUsage(?int $maxUsage): self
    {
        $this->maxUsage = $maxUsage;

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

}
