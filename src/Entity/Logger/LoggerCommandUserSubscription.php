<?php

namespace App\Entity\Logger;

use App\Entity\Commerce\CommercePackage;
use App\Entity\Commerce\CommerceUserSubscription;
use App\Entity\Core\CoreUser;
use App\Repository\Logger\LoggerCommandUserSubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoggerCommandUserSubscriptionRepository::class)
 */
class LoggerCommandUserSubscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CoreUser::class, inversedBy="loggerCommandUserSubscriptions")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=CommercePackage::class)
     */
    private $package;

    /**
     * @ORM\ManyToOne(targetEntity=CommerceUserSubscription::class)
     */
    private $subscription;

    /**
     * @ORM\Column(type="boolean")
     */
    private $flagged;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $flagType;

    /**
     * @ORM\Column(type="integer")
     */
    private $response;


    /**
     * LoggerCommandUserSubscription constructor.
     *
     * @param int $response
     * @param ?CoreUser $user
     * @param ?CommercePackage $package
     * @param ?CommerceUserSubscription $subscription
     * @param bool $flagged
     * @param string $flagType
     */
    public function __construct(
        int $response,
        ?CoreUser $user = null,
        ?CommercePackage $package = null,
        ?CommerceUserSubscription $subscription = null,
        bool $flagged = false,
        string $flagType = ""
    )
    {
        $this->user = $user;
        $this->package = $package;
        $this->subscription = $subscription;
        $this->response = $response;
        $this->flagged = $flagged;
        $this->flagType = $flagType;
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

    public function getPackage(): ?CommercePackage
    {
        return $this->package;
    }

    public function setPackage(?CommercePackage $package): self
    {
        $this->package = $package;

        return $this;
    }

    public function getSubscription(): ?CommerceUserSubscription
    {
        return $this->subscription;
    }

    public function setSubscription(?CommerceUserSubscription $subscription): self
    {
        $this->subscription = $subscription;

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

    public function getResponse(): ?int
    {
        return $this->response;
    }

    public function setResponse(int $response): self
    {
        $this->response = $response;

        return $this;
    }
}
