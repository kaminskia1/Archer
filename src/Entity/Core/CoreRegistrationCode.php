<?php

namespace App\Entity\Core;

use App\Model\CoreTraitModel;
use App\Repository\Core\CoreRegistrationCodeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=CoreRegistrationCodeRepository::class)
 */
class CoreRegistrationCode
{

    use CoreTraitModel;

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
     * @ORM\OneToOne(targetEntity=App\Entity\Core\CoreUser::class, cascade={"persist", "remove"})
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

    public function getUsedBy(): ?CoreUser
    {
        return $this->usedBy;
    }

    public function setUsedBy(?CoreUser $usedBy): self
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
