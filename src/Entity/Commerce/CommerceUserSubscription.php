<?php

namespace App\Entity\Commerce;

use App\Repository\Commerce\CommerceUserSubscriptionRepository;
use DateInterval;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommerceUserSubscriptionRepository::class)
 */
class CommerceUserSubscription
{

    public function __construct( ?CommerceInvoice $invoice )
    {
        $exp = new \DateTime();
        if ( $invoice instanceof CommerceInvoice )
        {
            $this->setUser( $invoice->getUser() );
            $this->setCommercePackageAssoc( $invoice->getCommercePackage() );
            $exp->add( $invoice->getDurationDateInterval() );
        }
        $this->setExpiryDateTime( $exp );
    }

    public function _addTime( \DateInterval $interval )
    {

        $exp = clone $this->getExpiryDateTime();
        if ($exp < new \DateTime() )
        {
            $exp = new \DateTime();
        }
        $exp->add( $interval );
        $this->setExpiryDateTime( $exp );
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\Core\User::class, inversedBy="CommerceUserSubscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\Commerce\CommercePackage::class, inversedBy="commerceUserSubscriptions")
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCommercePackageAssoc(): ?CommercePackage
    {
        return $this->commercePackageAssoc;
    }

    public function setCommercePackageAssoc(?CommercePackage $commercePackageAssoc): self
    {
        $this->commercePackageAssoc = $commercePackageAssoc;

        return $this;
    }

    public function getExpiryDateTime(): ?\DateTimeInterface
    {
        return $this->expiryDateTime;
    }

    public function setExpiryDateTime(\DateTimeInterface $expiryDateTime): self
    {
        $this->expiryDateTime = $expiryDateTime;

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
}
