<?php

namespace App\Entity\Core;

use App\Entity\Commerce\CommerceInvoice;
use App\Entity\Commerce\CommerceLicenseKey;
use App\Entity\Commerce\CommercePurchase;
use App\Entity\Commerce\CommerceUserSubscription;
use App\Entity\Logger\LoggerCommandAuth;
use App\Entity\Logger\LoggerCommandUserInfraction;
use App\Entity\Logger\LoggerCommandUserSubscription;
use App\Model\CoreTraitModel;
use App\Repository\Core\CoreUserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;


/**
 * @ORM\Entity(repositoryClass=CoreUserRepository::class)
 * @UniqueEntity(fields={"uuid"}, message="There is already an account with this uuid")
 */
class CoreUser implements UserInterface
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
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $uuid;

    /**
     * @TODO Deprecate this
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
    private $staffNote = "";

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $plainPassword;

    /**
     * @ORM\OneToOne(targetEntity=CoreRegistrationCode::class, cascade={"persist", "remove"})
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
    private $lastSiteLoginDate;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hwid;

    /**
     * @ORM\OneToMany(targetEntity=CommerceUserSubscription::class, mappedBy="user",
     *                                                                                   orphanRemoval=true)
     */
    private $CommerceUserSubscriptions;

    /**
     * @ORM\OneToMany(targetEntity=CommerceInvoice::class, mappedBy="user")
     */
    private $CommerceInvoices;

    /**
     * @ORM\OneToMany(targetEntity=CommercePurchase::class, mappedBy="user")
     */
    private $CommercePurchases;

    /**
     * @ORM\Column(type="string", length=65, nullable=true)
     */
    private $apiKey;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $apiKeyExpiry;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $apiAesKey;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $apiAesIV;

    /**
     * @ORM\Column(type="integer")
     */
    private $infractionPoints = 0;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $infractionTypes = [];

    /**
     * @ORM\Column(type="string", length=46, nullable=true)
     */
    private $lastSiteIP = "";

    /**
     * @ORM\Column(type="string", length=46, nullable=true)
     */
    private $lastLoaderIP = "";

    /**
     * @ORM\Column(type="array", nullable=false)
     */
    private $siteIPCollection = [];

    /**
     * @ORM\Column(type="array", nullable=false)
     */
    private $loaderIPCollection = [];

    /**
     * @ORM\OneToMany(targetEntity=LoggerCommandAuth::class, mappedBy="user")
     */
    private $loggerCommandAuths;

    /**
     * @ORM\OneToMany(targetEntity=LoggerCommandUserInfraction::class, mappedBy="user")
     */
    private $loggerCommandUserInfractions;

    /**
     * @ORM\OneToMany(targetEntity=LoggerCommandUserSubscription::class, mappedBy="user")
     */
    private $loggerCommandUserSubscriptions;

    /**
     * @ORM\ManyToMany(targetEntity=CoreGroup::class, inversedBy="users")
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity=CommerceLicenseKey::class, mappedBy="usedBy")
     */
    private $commerceLicenseKeys;

    /**
     * CoreUser constructor.
     */
    public function __construct()
    {
        $this->CommerceUserSubscriptions = new ArrayCollection();
        $this->CommerceInvoices = new ArrayCollection();
        $this->CommercePurchases = new ArrayCollection();
        $this->loggerCommandAuths = new ArrayCollection();
        $this->siteIPCollection = $this->siteIPCollection ?? [];
        $this->loaderIPCollection = $this->loaderIPCollection ?? [];
        $this->loggerCommandUserInfractions = new ArrayCollection();
        $this->loggerCommandUserSubscriptions = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->commerceLicenseKeys = new ArrayCollection();
    }

    public function isBanned(): bool
    {
        return in_array("ROLE_BANNED", $this->getRoles());
    }

    /**
     * Get roles
     *
     * @return array
     * @see UserInterface
     */
    public function getRoles(): array
    {

        $roles = [];

        foreach ($this->getGroups() as $group) {
            $this->_rec($roles, $group);
        }

        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @return Collection|CoreGroup[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function setGroups($groups): self
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * Recursive call for getRoles
     *
     * @param           $array
     * @param CoreGroup $group
     */
    private function _rec(&$array, CoreGroup $group)
    {
        $array[] = $group->getInternalName();
        foreach ($group->getInherits() as $i) {
            if (!in_array($i->getInternalName(), $array)) {
                $this->_rec($array, $i);
            }
        }
    }

    /**
     * Get the user's highest-priority corresponding group color. Only iterates over directly owned groups
     *
     * @return string
     */
    public function getColor(): string
    {
        $color = "";
        $priority = 0;
        foreach ($this->getGroups() as $group) {
            if ($group->getPriority() > $priority) {
                $color = $group->getColor();
                $priority = $group->getPriority();
            }
        }
        return $color;
    }

    /**
     * Convert this entity to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return strlen($this->getNickname()) > 0 ? $this->getNickname() : $this->getUuid();
    }

    /**
     * Get nickname
     *
     * @return string|null
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * Set nickname
     *
     * @param string|null $nickname
     *
     * @return $this
     */
    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * Set uuid
     *
     * @param string $uuid
     *
     * @return $this
     */
    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Populate empty fields with generated data
     *
     * @throws Exception
     */
    public function __populate()
    {
        $tmp = [];
        for ($i = 0; $i < 6; $i++) array_push($tmp, random_int(111111, 999999));

        $this->backupCodes = $this->backupCodes ?? $this->setBackupCodes($tmp);
        $this->uuid = $this->uuid ?? Uuid::v5(Uuid::v4(), $this->registrationCode->getCode());
        $this->staffNote = $this->staffNote ?? "";
        $this->nickname = $this->nickname ?? "";
        $this->registrationDate = $this->registrationDate ?? new DateTime();
    }

    public function hasRole(string $role)
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->uuid;
    }

    /**
     * Get salt
     *
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * Erase credentials
     *
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
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
     * Get password
     *
     * @return string
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get backup codes
     *
     * @return array|null
     */
    public function getBackupCodes(): ?array
    {
        return $this->backupCodes;
    }

    /**
     * Set backup codes
     *
     * @param array $backupCodes
     *
     * @return $this
     */
    public function setBackupCodes(array $backupCodes): self
    {
        $this->backupCodes = $backupCodes;

        return $this;
    }

    /**
     * Get staff note
     *
     * @return string|null
     */
    public function getStaffNote(): ?string
    {
        return $this->staffNote;
    }

    /**
     * Set staff note
     *
     * @param string|null $staffNote
     *
     * @return $this
     */
    public function setStaffNote(?string $staffNote): self
    {
        $this->staffNote = $staffNote;

        return $this;
    }

    /**
     * Get plain password
     *
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * Set plain password
     *
     * @param string|null $plainPassword
     *
     * @return $this
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Get registration code
     *
     * @return CoreRegistrationCode|null
     */
    public function getRegistrationCode(): ?CoreRegistrationCode
    {
        return $this->registrationCode;
    }

    /**
     * Set registration code
     *
     * @param CoreRegistrationCode $registrationCode
     *
     * @return $this
     */
    public function setRegistrationCode(CoreRegistrationCode $registrationCode): self
    {
        $this->registrationCode = $registrationCode;

        return $this;
    }

    /**
     * Get registration date
     *
     * @return DateTimeInterface|null
     */
    public function getRegistrationDate(): ?DateTimeInterface
    {
        return $this->registrationDate;
    }

    /**
     * Set registratio date
     *
     * @param DateTimeInterface $registrationDate
     *
     * @return $this
     */
    public function setRegistrationDate(DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    /**
     * Get last login date
     *
     * @return DateTimeInterface|null
     */
    public function getLastLoginDate(): ?DateTimeInterface
    {
        return $this->lastLoginDate;
    }

    /**
     * Set last login date
     *
     * @param DateTimeInterface|null $lastLoginDate
     *
     * @return $this
     */
    public function setLastLoginDate(?DateTimeInterface $lastLoginDate): self
    {
        $this->lastLoginDate = $lastLoginDate;

        return $this;
    }

    /**
     * Get last loader login date
     *
     * @return DateTimeInterface|null
     */
    public function getLastLoaderLoginDate(): ?DateTimeInterface
    {
        return $this->lastLoaderLoginDate;
    }

    /**
     * Set last loader login date
     *
     * @param DateTimeInterface|null $lastLoaderLoginDate
     *
     * @return $this
     */
    public function setLastLoaderLoginDate(?DateTimeInterface $lastLoaderLoginDate): self
    {
        $this->lastLoaderLoginDate = $lastLoaderLoginDate;

        return $this;
    }

    /**
     * Get last website login date
     *
     * @return DateTimeInterface|null
     */
    public function getLastSiteLoginDate(): ?DateTimeInterface
    {
        return $this->lastSiteLoginDate;
    }

    /**
     * Set last website login date
     *
     * @param DateTimeInterface|null $lastSiteLoginDate
     *
     * @return $this
     */
    public function setLastSiteLoginDate(?DateTimeInterface $lastSiteLoginDate): self
    {
        $this->lastSiteLoginDate = $lastSiteLoginDate;

        return $this;
    }

    /**
     * Get hwid
     *
     * @return string|null
     */
    public function getHwid(): ?string
    {
        return $this->hwid;
    }

    /**
     * Set hwid
     *
     * @param string|null $hwid
     *
     * @return $this
     */
    public function setHwid(?string $hwid): self
    {
        $this->hwid = $hwid;

        return $this;
    }

    /**
     * Get commerce user subscriptions
     *
     * @return Collection|CommerceUserSubscription[]
     */
    public function getCommerceUserSubscriptions(): Collection
    {
        return $this->CommerceUserSubscriptions;
    }

    /**
     * Add commerce user subscription
     *
     * @param CommerceUserSubscription $commerceUserSubscription
     *
     * @return $this
     */
    public function addCommerceUserSubscription(CommerceUserSubscription $commerceUserSubscription): self
    {
        if (!$this->CommerceUserSubscriptions->contains($commerceUserSubscription)) {
            $this->CommerceUserSubscriptions[] = $commerceUserSubscription;
            $commerceUserSubscription->setUser($this);
        }

        return $this;
    }

    /**
     * Remove commerce user subscription
     *
     * @param CommerceUserSubscription $commerceUserSubscription
     *
     * @return $this
     */
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
     * Get commerce invoices
     *
     * @return Collection|CommerceInvoice[]
     */
    public function getCommerceInvoices(): Collection
    {
        return $this->CommerceInvoices;
    }

    /**
     * Add commerce invoice
     *
     * @param CommerceInvoice $commerceInvoice
     *
     * @return $this
     */
    public function addCommerceInvoice(CommerceInvoice $commerceInvoice): self
    {
        if (!$this->CommerceInvoices->contains($commerceInvoice)) {
            $this->CommerceInvoices[] = $commerceInvoice;
            $commerceInvoice->setUser($this);
        }

        return $this;
    }

    /**
     * Remove commmerce invoice
     *
     * @param CommerceInvoice $commerceInvoice
     *
     * @return $this
     */
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
     * Get commerce purchases
     *
     * @return Collection|CommercePurchase[]
     */
    public function getCommercePurchases(): Collection
    {
        return $this->CommercePurchases;
    }

    /**
     * Add commerce purchase
     *
     * @param CommercePurchase $commercePurchase
     *
     * @return $this
     */
    public function addCommercePurchase(CommercePurchase $commercePurchase): self
    {
        if (!$this->CommercePurchases->contains($commercePurchase)) {
            $this->CommercePurchases[] = $commercePurchase;
            $commercePurchase->setUser($this);
        }

        return $this;
    }

    /**
     * Remove commerce purchase
     *
     * @param CommercePurchase $commercePurchase
     *
     * @return $this
     */
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
     * Get api key
     *
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * Set api key
     *
     * @param string|null $apiKey
     *
     * @return $this
     */
    public function setApiKey(?string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get api key expiry
     *
     * @return DateTimeInterface|null
     */
    public function getApiKeyExpiry(): ?DateTimeInterface
    {
        return $this->apiKeyExpiry;
    }

    /**
     * Set api key expiry
     *
     * @param DateTimeInterface|null $apiKeyExpiry
     *
     * @return $this
     */
    public function setApiKeyExpiry(?DateTimeInterface $apiKeyExpiry): self
    {
        $this->apiKeyExpiry = $apiKeyExpiry;

        return $this;
    }

    /**
     * Get api aes key
     *
     * @return string|null
     */
    public function getApiAesKey(): ?string
    {
        return $this->apiAesKey;
    }

    /**
     * Set api aes key
     *
     * @param string|null $apiAesKey
     *
     * @return $this
     */
    public function setApiAesKey(?string $apiAesKey): self
    {
        $this->apiAesKey = $apiAesKey;

        return $this;
    }

    /**
     * Get api aes iv
     *
     * @return string|null
     */
    public function getApiAesIV(): ?string
    {
        return $this->apiAesIV;
    }

    /**
     * Set api aes iv
     *
     * @param string|null $apiAesIV
     *
     * @return $this
     */
    public function setApiAesIV(?string $apiAesIV): self
    {
        $this->apiAesIV = $apiAesIV;

        return $this;
    }

    /**
     * Add infraction points
     *
     * @param int $infractionPoints
     *
     * @return $this
     */
    public function addInfractionPoints(int $infractionPoints): self
    {
        // if prev < 500 < new
        if ($this->getInfractionPoints() < 500 && ($this->getInfractionPoints() + $infractionPoints) >= 500) {
            $this->doBanUser();
        }
        $this->infractionPoints = ($this->infractionPoints ?? 0) + $infractionPoints;

        return $this;
    }

    /**
     * Get infraction points
     *
     * @return int
     */
    public function getInfractionPoints(): int
    {
        return $this->infractionPoints;
    }

    /**
     * Set infraction points
     *
     * @param int $infractionPoints
     *
     * @return $this
     */
    public function setInfractionPoints(int $infractionPoints): self
    {
        $this->infractionPoints = $infractionPoints;

        return $this;
    }

    /**
     * Ban this user
     */
    public function doBanUser()
    {
        /**
         * @var EntityManagerInterface $em
         */
        $em = $GLOBALS['kernel']
            ->getContainer()
            ->get('doctrine.orm.entity_manager');

        if (!in_array("ROLE_BANNED", $this->getRoles())) {
            $this->addGroup($em->getRepository(CoreGroup::class)->findOneBy(['internalName' => 'ROLE_BANNED']));
            $em->flush();
        }

    }

    public function addGroup(CoreGroup $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addUser($this);
        }

        return $this;
    }

    /**
     * Get infraction types
     *
     * @return array|null
     */
    public function getInfractionTypes(): ?array
    {
        return $this->infractionTypes;
    }

    /**
     * Set infraction types
     *
     * @param array|null $infractionTypes
     *
     * @return $this
     */
    public function setInfractionTypes(?array $infractionTypes): self
    {
        $this->infractionTypes = $infractionTypes;

        return $this;
    }

    /**
     * Add infraction type
     *
     * @param int $value
     *
     * @return $this
     */
    public function addInfractionType(int $value): self
    {
        if (!is_array($this->infractionTypes)) {
            $this->infractionTypes = [];
        }
        $this->infractionTypes = array_merge($this->infractionTypes, [$value]);

        return $this;
    }

    /**
     * Get last site ip
     *
     * @return string|null
     */
    public function getLastSiteIP(): ?string
    {
        return $this->lastSiteIP;
    }

    /**
     * Set last site ip
     *
     * @param string|null $lastSiteIP
     *
     * @return $this
     */
    public function setLastSiteIP(?string $lastSiteIP): self
    {
        $this->lastSiteIP = $lastSiteIP;

        return $this;
    }

    /**
     * Get last loader ip
     *
     * @return string|null
     */
    public function getLastLoaderIP(): ?string
    {
        return $this->lastLoaderIP;
    }

    /**
     * Set last loader ip
     *
     * @param string|null $lastLoaderIP
     *
     * @return $this
     */
    public function setLastLoaderIP(?string $lastLoaderIP): self
    {
        $this->lastLoaderIP = $lastLoaderIP;

        return $this;
    }

    /**
     * Get site ip collection
     *
     * @return array|null
     */
    public function getSiteIPCollection(): ?array
    {
        return $this->siteIPCollection;
    }

    /**
     * Set site ip collection
     *
     * @param array|null $siteIPCollection
     *
     * @return $this
     */
    public function setSiteIPCollection(?array $siteIPCollection): self
    {
        $this->siteIPCollection = $siteIPCollection;

        return $this;
    }

    /**
     * Register a new site IP
     *
     * @param string $ip
     *
     * @return $this
     */
    public function registerSiteIP(string $ip): self
    {
        $this->lastSiteIP = $ip;
        if (!is_array($this->siteIPCollection)) {
            $this->siteIPCollection = [];
        }
        if (!in_array($ip, $this->siteIPCollection)) {
            array_push($this->siteIPCollection, $ip);
        }
        return $this;
    }

    /**
     * Get loader ip collection
     *
     * @return array|null
     */
    public function getLoaderIPCollection(): ?array
    {
        return $this->loaderIPCollection;
    }

    /**
     * Set loader ip collection
     *
     * @param array|null $loaderIPCollection
     *
     * @return $this
     */
    public function setLoaderIPCollection(?array $loaderIPCollection): self
    {
        $this->loaderIPCollection = $loaderIPCollection;

        return $this;
    }

    /**
     * Register a new loader IP
     *
     * @param string $ip
     *
     * @return $this
     */
    public function registerLoaderIP(string $ip): self
    {
        $this->lastLoaderIP = $ip;
        if (!is_array($this->loaderIPCollection)) {
            $this->loaderIPCollection = [];
        }
        if (!in_array($ip, $this->loaderIPCollection)) {
            array_push($this->loaderIPCollection, $ip);
        }
        return $this;
    }

    /**
     * @return Collection|LoggerCommandAuth[]
     */
    public function getLoggerCommandAuths(): Collection
    {
        return $this->loggerCommandAuths;
    }

    public function addLoggerCommandAuth(LoggerCommandAuth $loggerCommandAuth): self
    {
        if (!$this->loggerCommandAuths->contains($loggerCommandAuth)) {
            $this->loggerCommandAuths[] = $loggerCommandAuth;
            $loggerCommandAuth->setUser($this);
        }

        return $this;
    }

    public function removeLoggerCommandAuth(LoggerCommandAuth $loggerCommandAuth): self
    {
        if ($this->loggerCommandAuths->removeElement($loggerCommandAuth)) {
            // set the owning side to null (unless already changed)
            if ($loggerCommandAuth->getUser() === $this) {
                $loggerCommandAuth->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LoggerCommandUserInfraction[]
     */
    public function getLoggerCommandUserInfractions(): Collection
    {
        return $this->loggerCommandUserInfractions;
    }

    public function addLoggerCommandUserInfraction(LoggerCommandUserInfraction $loggerCommandUserInfraction): self
    {
        if (!$this->loggerCommandUserInfractions->contains($loggerCommandUserInfraction)) {
            $this->loggerCommandUserInfractions[] = $loggerCommandUserInfraction;
            $loggerCommandUserInfraction->setUser($this);
        }

        return $this;
    }

    public function removeLoggerCommandUserInfraction(LoggerCommandUserInfraction $loggerCommandUserInfraction): self
    {
        if ($this->loggerCommandUserInfractions->removeElement($loggerCommandUserInfraction)) {
            // set the owning side to null (unless already changed)
            if ($loggerCommandUserInfraction->getUser() === $this) {
                $loggerCommandUserInfraction->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LoggerCommandUserSubscription[]
     */
    public function getLoggerCommandUserSubscriptions(): Collection
    {
        return $this->loggerCommandUserSubscriptions;
    }

    public function addLoggerCommandUserSubscription(LoggerCommandUserSubscription $loggerCommandUserSubscription): self
    {
        if (!$this->loggerCommandUserSubscriptions->contains($loggerCommandUserSubscription)) {
            $this->loggerCommandUserSubscriptions[] = $loggerCommandUserSubscription;
            $loggerCommandUserSubscription->setUser($this);
        }

        return $this;
    }

    public function removeLoggerCommandUserSubscription(LoggerCommandUserSubscription $loggerCommandUserSubscription): self
    {
        if ($this->loggerCommandUserSubscriptions->removeElement($loggerCommandUserSubscription)) {
            // set the owning side to null (unless already changed)
            if ($loggerCommandUserSubscription->getUser() === $this) {
                $loggerCommandUserSubscription->setUser(null);
            }
        }

        return $this;
    }

    public function removeGroup(CoreGroup $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeUser($this);
        }

        return $this;
    }

    public function getHighestGroup(): CoreGroup
    {
        $highest = PHP_INT_MIN;
        $currentGroup = $this->groups->first();
        foreach ($this->groups as $g) {
            if ($g->getPriority() > $highest) {
                $currentGroup = $g;
            }
        }
        return $currentGroup;
    }

    /**
     * @return Collection|CommerceLicenseKey[]
     */
    public function getCommerceLicenseKeys(): Collection
    {
        return $this->commerceLicenseKeys;
    }

    public function addCommerceLicenseKey(CommerceLicenseKey $commerceLicenseKey): self
    {
        if (!$this->commerceLicenseKeys->contains($commerceLicenseKey)) {
            $this->commerceLicenseKeys[] = $commerceLicenseKey;
            $commerceLicenseKey->setUsedBy($this);
        }

        return $this;
    }

    public function removeCommerceLicenseKey(CommerceLicenseKey $commerceLicenseKey): self
    {
        if ($this->commerceLicenseKeys->removeElement($commerceLicenseKey)) {
            // set the owning side to null (unless already changed)
            if ($commerceLicenseKey->getUsedBy() === $this) {
                $commerceLicenseKey->setUsedBy(null);
            }
        }

        return $this;
    }

}
