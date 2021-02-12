<?php

namespace App\Entity;

use App\Repository\RegistrationCodeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=RegistrationCodeRepository::class)
 */
class RegistrationCode
{

    public function __toString()
    {
        return $this->getCode();
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
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $usageDate;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $usedBy;

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

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getUsageDate(): ?\DateTimeInterface
    {
        return $this->usageDate;
    }

    public function setUsageDate(?\DateTimeInterface $usageDate): self
    {
        $this->usageDate = $usageDate;

        return $this;
    }

    public function getUsedBy(): ?User
    {
        return $this->usedBy;
    }

    public function setUsedBy(?User $usedBy): self
    {
        $this->usedBy = $usedBy;

        return $this;
    }

    public function populateCode(): self
    {
        $this->setCode(Uuid::v4());
        return $this;
    }
}
