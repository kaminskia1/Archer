<?php

namespace App\Entity\Core;

use App\Model\CoreTraitModel;
use App\Repository\Core\CoreRegistrationCodeRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;


/**
 * @ORM\Entity(repositoryClass=CoreRegistrationCodeRepository::class)
 */
class CoreRegistrationCode
{

    /**
     * Import the base module
     */
    use CoreTraitModel;


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
     * @ORM\OneToOne(targetEntity=\App\Entity\Core\CoreUser::class, cascade={"persist", "remove"})
     */
    private $usedBy;


    /**
     * Convert this entity to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCode() ?? "Registration Code";
    }

    /**
     * Populate required empty fields with data
     *
     * @return $this
     */
    public function populateCode(): self
    {
        $this->setCode(Uuid::v4());
        return $this;
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
     * Get id
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get enabled state
     *
     * @return bool|null
     */
    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * Set enabled state
     *
     * @param bool $enabled
     * @return $this
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get usage date
     *
     * @return DateTimeInterface|null
     */
    public function getUsageDate(): ?DateTimeInterface
    {
        return $this->usageDate;
    }

    /**
     * Set usage date
     *
     * @param DateTimeInterface|null $usageDate
     * @return $this
     */
    public function setUsageDate(?DateTimeInterface $usageDate): self
    {
        $this->usageDate = $usageDate;

        return $this;
    }

    /**
     * Get used by
     *
     * @return CoreUser|null
     */
    public function getUsedBy(): ?CoreUser
    {
        return $this->usedBy;
    }

    /**
     * Set used by
     *
     * @param CoreUser|null $usedBy
     * @return $this
     */
    public function setUsedBy(?CoreUser $usedBy): self
    {
        $this->usedBy = $usedBy;

        return $this;
    }

}
