<?php

namespace App\Entity\Core;

use App\Repository\Core\CoreGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CoreGroupRepository::class)
 * @UniqueEntity("internalName")
 */
class CoreGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $internalName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $permissions = [];

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $color;

    /**
     * @ORM\ManyToMany(targetEntity=CoreUser::class, mappedBy="groups")
     */
    private $users;
    
    /**
     * @ORM\ManyToMany(targetEntity=CoreGroup::class)
     */
    private $inherits;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority;


    /**
     * CoreGroup constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->inherits = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPermissions(): ?array
    {
        return $this->permissions;
    }

    public function setPermissions(?array $permissions): self
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection|CoreUser[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }

    public function addUser(CoreUser $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(CoreUser $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getInternalName(): ?string
    {
        return $this->internalName;
    }

    public function setInternalName(string $internalName): self
    {
        $this->internalName = $internalName;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getInherits(): Collection
    {
        return $this->inherits;
    }

    public function addInherit(self $inherit): self
    {
        if (!$this->inherits->contains($inherit)) {
            $this->inherits[] = $inherit;
        }

        return $this;
    }

    public function removeInherit(self $inherit): self
    {
        $this->inherits->removeElement($inherit);

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Recursively grab every inherited group
     *
     * @return array
     */
    public function getAllInherits(): array
    {
        $array = [];
        foreach ($this->getInherits() as $group) {
            $this->_rec($array, $group);
        }
        return array_unique($array);
    }

    /**
     * Recursive call for getAllInherits
     *
     * @param           $array
     * @param CoreGroup $group
     */
    private function _rec(&$array, CoreGroup $group)
    {
        $array[] = $group;
        foreach ($group->getInherits() as $i) {
            if (!in_array($i, $array)) {
                $this->_rec($array, $i);
            }
        }
    }
}
