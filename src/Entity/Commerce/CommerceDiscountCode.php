<?php

namespace App\Entity\Commerce;

use App\Repository\Commerce\CommerceDiscountCodeRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=CommerceDiscountCodeRepository::class)
 */
class CommerceDiscountCode
{

    public function __toString()
    {
        return $this->code . " (" . ($this->type == "TYPE_PERCENT" ? $this->amount . "%" : "$" . $this->amount) . ")";
    }

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


    public function populate(): self
    {
        $this->code = $this->code ?? Uuid::v4();
        $this->currentUsage = $this->currentUsage ?? 0;
        $this->maxUsage = $this->maxUsage ?? -1;
        $this->staffMessage = $this->staffMessage ?? "";
        $this->isEnabled = $this->isEnabled ?? false;
        $this->expiryDate = $this->expiryDate ?? new DateTime(2038-1-1);

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getExpiryDate(): ?\DateTimeInterface
    {
        return $this->expiryDate;
    }

    public function setExpiryDate(?\DateTimeInterface $expiryDate): self
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    public function getCurrentUsage(): ?int
    {
        return $this->currentUsage;
    }

    public function setCurrentUsage(int $currentUsage): self
    {
        $this->currentUsage = $currentUsage;

        return $this;
    }

    public function getMaxUsage(): ?int
    {
        return $this->maxUsage;
    }

    public function setMaxUsage(?int $maxUsage): self
    {
        $this->maxUsage = $maxUsage;

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

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

}
