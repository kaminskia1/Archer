<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"uuid"}, message="There is already an account with this uuid")
 */
class User implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $backupCodes = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $staffNote;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $plainPassword;

    /**
     * @ORM\OneToOne(targetEntity=RegistrationCode::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $registrationCode;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registrationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLoginDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastLoaderLoginDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastWebsiteLoginDate;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hwid;

    /**
     * @ORM\OneToMany(targetEntity=CommerceUserSubscription::class, mappedBy="User", orphanRemoval=true)
     */
    private $CommerceUserSubscriptions;

    /**
     * @ORM\OneToMany(targetEntity=CommerceInvoice::class, mappedBy="User")
     */
    private $CommerceInvoices;

    /**
     * @ORM\OneToMany(targetEntity=CommercePurchase::class, mappedBy="User")
     */
    private $CommercePurchases;

    /**
     * @ORM\OneToMany(targetEntity=CommerceTransaction::class, mappedBy="user")
     */
    private $commerceTransactions;

    public function __construct()
    {
        $this->CommerceUserSubscriptions = new ArrayCollection();
        $this->CommerceInvoices = new ArrayCollection();
        $this->CommercePurchases = new ArrayCollection();
        $this->commerceTransactions = new ArrayCollection();
    }

    public function __toString()
    {
        return strlen($this->getNickname()) > 0 ? $this->getNickname() : $this->getUuid();
    }

    public function __populate()
    {
        $tmp = [];
        for ($i = 0; $i < 6; $i++) array_push($tmp, random_int(111111, 999999));

        $this->backupCodes = $this->backupCodes ?? $this->setBackupCodes($tmp);
        $this->uuid = $this->uuid ?? Uuid::v5( Uuid::v4(), $this->registrationCode->getCode() );
        $this->staffNote = $this->staffNote ?? "";
        $this->nickname = $this->nickname ?? "";
        $this->registrationDate = $this->registrationDate ?? new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }


    public function getBackupCodes(): ?array
    {
        return $this->backupCodes;
    }

    public function setBackupCodes(array $backupCodes): self
    {
        $this->backupCodes = $backupCodes;

        return $this;
    }

    public function getStaffNote(): ?string
    {
        return $this->staffNote;
    }

    public function setStaffNote(?string $staffNote): self
    {
        $this->staffNote = $staffNote;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getRegistrationCode(): ?RegistrationCode
    {
        return $this->registrationCode;
    }

    public function setRegistrationCode(RegistrationCode $registrationCode): self
    {
        $this->registrationCode = $registrationCode;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getLastLoginDate(): ?\DateTimeInterface
    {
        return $this->lastLoginDate;
    }

    public function setLastLoginDate(?\DateTimeInterface $lastLoginDate): self
    {
        $this->lastLoginDate = $lastLoginDate;

        return $this;
    }

    public function getLastLoaderLoginDate(): ?\DateTimeInterface
    {
        return $this->lastLoaderLoginDate;
    }

    public function setLastLoaderLoginDate(?\DateTimeInterface $lastLoaderLoginDate): self
    {
        $this->lastLoaderLoginDate = $lastLoaderLoginDate;

        return $this;
    }

    public function getLastWebsiteLoginDate(): ?\DateTimeInterface
    {
        return $this->lastWebsiteLoginDate;
    }

    public function setLastWebsiteLoginDate(?\DateTimeInterface $lastWebsiteLoginDate): self
    {
        $this->lastWebsiteLoginDate = $lastWebsiteLoginDate;

        return $this;
    }

    public function getHwid(): ?string
    {
        return $this->hwid;
    }

    public function setHwid(?string $hwid): self
    {
        $this->hwid = $hwid;

        return $this;
    }

    /**
     * @return Collection|CommerceUserSubscription[]
     */
    public function getCommerceUserSubscriptions(): Collection
    {
        return $this->CommerceUserSubscriptions;
    }

    public function addCommerceUserSubscription(CommerceUserSubscription $commerceUserSubscription): self
    {
        if (!$this->CommerceUserSubscriptions->contains($commerceUserSubscription)) {
            $this->CommerceUserSubscriptions[] = $commerceUserSubscription;
            $commerceUserSubscription->setUser($this);
        }

        return $this;
    }

    public function removeCommerceUserSubscription(CommerceUserSubscription $commerceUserSubscription): self
    {
        if ($this->CommerceUserSubscriptions->removeElement($commerceUserSubscription)) {
            // set the owning side to null (unless already changed)
            if ($commerceUserSubscription->getUser() === $this) {
                $commerceUserSubscription->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommerceInvoice[]
     */
    public function getCommerceInvoices(): Collection
    {
        return $this->CommerceInvoices;
    }

    public function addCommerceInvoice(CommerceInvoice $commerceInvoice): self
    {
        if (!$this->CommerceInvoices->contains($commerceInvoice)) {
            $this->CommerceInvoices[] = $commerceInvoice;
            $commerceInvoice->setUser($this);
        }

        return $this;
    }

    public function removeCommerceInvoice(CommerceInvoice $commerceInvoice): self
    {
        if ($this->CommerceInvoices->removeElement($commerceInvoice)) {
            // set the owning side to null (unless already changed)
            if ($commerceInvoice->getUser() === $this) {
                $commerceInvoice->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommercePurchase[]
     */
    public function getCommercePurchases(): Collection
    {
        return $this->CommercePurchases;
    }

    public function addCommercePurchase(CommercePurchase $commercePurchase): self
    {
        if (!$this->CommercePurchases->contains($commercePurchase)) {
            $this->CommercePurchases[] = $commercePurchase;
            $commercePurchase->setUser($this);
        }

        return $this;
    }

    public function removeCommercePurchase(CommercePurchase $commercePurchase): self
    {
        if ($this->CommercePurchases->removeElement($commercePurchase)) {
            // set the owning side to null (unless already changed)
            if ($commercePurchase->getUser() === $this) {
                $commercePurchase->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommerceTransaction[]
     */
    public function getCommerceTransactions(): Collection
    {
        return $this->commerceTransactions;
    }

    public function addCommerceTransaction(CommerceTransaction $commerceTransaction): self
    {
        if (!$this->commerceTransactions->contains($commerceTransaction)) {
            $this->commerceTransactions[] = $commerceTransaction;
            $commerceTransaction->setUser($this);
        }

        return $this;
    }

    public function removeCommerceTransaction(CommerceTransaction $commerceTransaction): self
    {
        if ($this->commerceTransactions->removeElement($commerceTransaction)) {
            // set the owning side to null (unless already changed)
            if ($commerceTransaction->getUser() === $this) {
                $commerceTransaction->setUser(null);
            }
        }

        return $this;
    }
}
