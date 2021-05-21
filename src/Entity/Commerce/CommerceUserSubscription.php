<?php

namespace App\Entity\Commerce;

use App\Entity\Core\CoreUser;
use App\Model\CommerceTraitModel;
use App\Repository\Commerce\CommerceUserSubscriptionRepository;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CommerceUserSubscriptionRepository::class)
 */
class CommerceUserSubscription
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
     * @ORM\ManyToOne(targetEntity=\App\Entity\Core\CoreUser::class, inversedBy="CommerceUserSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=\App\Entity\Commerce\CommercePackage::class, inversedBy="commerceUserSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commercePackageAssoc;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiryDateTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $staffMessage;


    /**
     * CommerceUserSubscription constructor.
     *
     * @param CommerceInvoice|null $invoice
     */
    public function __construct(?CommerceInvoice $invoice)
    {
        $exp = new DateTime();

        // Check if invoice provided
        if ($invoice instanceof CommerceInvoice) {
            // Move over data
            $this->setUser($invoice->getUser());
            $this->setCommercePackageAssoc($invoice->getCommercePackage());
            $exp->add($invoice->getDurationDateInterval());
        }
        $this->setExpiryDateTime($exp);
    }

    /**
     * Add time to the subscription
     *
     * @param DateInterval $interval
     */
    public function addTime(DateInterval $interval)
    {

        $exp = clone $this->getExpiryDateTime();
        if ($exp < new DateTime()) {
            $exp = new DateTime();
        }
        $exp->add($interval);
        $this->setExpiryDateTime($exp);
    }

    /**
     * Get if the subscription is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->getExpiryDateTime() > new DateTime('now');
    }

    /**
     * Get if the subscription is expired
     *
     * @return bool
     */
    public function isExpired()
    {
        return !$this->isActive();
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
     * Get commerce package association
     *
     * @return CommercePackage|null
     */
    public function getCommercePackageAssoc(): ?CommercePackage
    {
        return $this->commercePackageAssoc;
    }

    /**
     * Set commerce package association
     *
     * @param CommercePackage|null $commercePackageAssoc
     * @return $this
     */
    public function setCommercePackageAssoc(?CommercePackage $commercePackageAssoc): self
    {
        $this->commercePackageAssoc = $commercePackageAssoc;

        return $this;
    }

    /**
     * Get expiry datetime
     *
     * @return DateTimeInterface|null
     */
    public function getExpiryDateTime(): ?DateTimeInterface
    {
        return $this->expiryDateTime;
    }

    /**
     * Set expiry datetime
     *
     * @param DateTimeInterface $expiryDateTime
     * @return $this
     */
    public function setExpiryDateTime(DateTimeInterface $expiryDateTime): self
    {
        $this->expiryDateTime = $expiryDateTime;

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

}
