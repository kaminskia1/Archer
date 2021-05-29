<?php

namespace App\Entity\Logger;

use App\Entity\Commerce\CommercePackage;
use App\Entity\Core\CoreUser;
use App\Repository\Logger\LoggerCommandAuthRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoggerCommandAuthRepository::class)
 */
class LoggerCommandAuth
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CoreUser::class, inversedBy="loggerCommandAuths")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=46, nullable=true)
     */
    private $ip;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $providedHwid;

    /**
     * @ORM\ManyToOne(targetEntity=CommercePackage::class)
     */
    private $package;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $response;

    /**
     * @ORM\Column(type="boolean")
     */
    private $flagged;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $flagType;

    /**
     * @ORM\Column(type="boolean")
     */
    private $bannedAtAttempt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $subscriptionExpiry;

    /**
     * @ORM\Column(type="integer")
     */
    private $currentInfractionPoints;

    /**
     * @ORM\Column(type="datetime")
     */
    private $execution;


    /**
     * LoggerCommandAuth constructor.
     *
     * @param CoreUser $user
     * @param string $ip
     * @param string $providedHwid
     * @param int $response
     * @param bool $flagged
     * @param string|null $flagType
     * @param CommercePackage|null $package
     * @param DateTime|null $subscriptionRemaining
     */
    public function __construct(
        CoreUser $user,
        string $ip,
        string $providedHwid,
        int $response,
        bool $flagged = false,
        ?string $flagType = null,
        ?CommercePackage $package = null,
        ?DateTime $subscriptionRemaining = null)
    {
        $this->user = $user;
        $this->ip = $ip;
        $this->providedHwid = $providedHwid;
        $this->response = $response;
        $this->package = $package;
        $this->flagged = $flagged;
        $this->flagType = $flagType;
        $this->bannedAtAttempt = in_array("ROLE_BANNED", $user->getRoles());
        $this->subscriptionExpiry = $subscriptionRemaining;
        $this->currentInfractionPoints = $user->getInfractionPoints();
        $this->execution = new DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getProvidedHwid(): ?string
    {
        return $this->providedHwid;
    }

    public function setProvidedHwid(string $providedHwid): self
    {
        $this->providedHwid = $providedHwid;

        return $this;
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

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(string $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getFlagged(): ?bool
    {
        return $this->flagged;
    }

    public function setFlagged(bool $flagged): self
    {
        $this->flagged = $flagged;

        return $this;
    }

    public function getFlagType(): ?string
    {
        return $this->flagType;
    }

    public function setFlagType(?string $flagType): self
    {
        $this->flagType = $flagType;

        return $this;
    }

    public function getBannedAtAttempt(): ?bool
    {
        return $this->bannedAtAttempt;
    }

    public function setBannedAtAttempt(bool $bannedAtAttempt): self
    {
        $this->bannedAtAttempt = $bannedAtAttempt;

        return $this;
    }

    public function getSubscriptionExpiry(): ?DateTimeInterface
    {
        return $this->subscriptionExpiry;
    }

    public function setSubscriptionExpiry(?DateTimeInterface $subscriptionExpiry): self
    {
        $this->subscriptionExpiry = $subscriptionExpiry;

        return $this;
    }

    public function getCurrentInfractionPoints(): ?int
    {
        return $this->currentInfractionPoints;
    }

    public function setCurrentInfractionPoints(int $currentInfractionPoints): self
    {
        $this->currentInfractionPoints = $currentInfractionPoints;

        return $this;
    }

    public function getExecution(): ?DateTimeInterface
    {
        return $this->execution;
    }

    public function setExecution(DateTimeInterface $execution): self
    {
        $this->execution = $execution;

        return $this;
    }
}
