<?php

namespace App\Entity\Logger;

use App\Entity\Core\CoreUser;
use App\Repository\Logger\LoggerCommandUserInfractionRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoggerCommandUserInfractionRepository::class)
 */
class LoggerCommandUserInfraction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=CoreUser::class, inversedBy="loggerCommandUserInfractions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $previousPoints;

    /**
     * @ORM\Column(type="integer")
     */
    private $addedPoints;

    /**
     * @ORM\Column(type="boolean")
     */
    private $banIssued;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $execution;


    /**
     * LoggerCommandUserInfraction constructor.
     *
     * @param CoreUser $user
     * @param int $previousPoints
     * @param int $addedPoints
     * @param bool $banIssued
     * @param string $type
     */
    public function __construct(CoreUser $user, int $previousPoints, int $addedPoints, string $type, bool $banIssued)
    {
        $this->user = $user;
        $this->previousPoints = $previousPoints;
        $this->addedPoints = $addedPoints;
        $this->type = $type;
        $this->banIssued = $banIssued;
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

    public function getPreviousPoints(): ?int
    {
        return $this->previousPoints;
    }

    public function setPreviousPoints(int $previousPoints): self
    {
        $this->previousPoints = $previousPoints;

        return $this;
    }

    public function getAddedPoints(): ?int
    {
        return $this->addedPoints;
    }

    public function setAddedPoints(int $addedPoints): self
    {
        $this->addedPoints = $addedPoints;

        return $this;
    }

    public function getBanIssued(): ?bool
    {
        return $this->banIssued;
    }

    public function setBanIssued(bool $banIssued): self
    {
        $this->banIssued = $banIssued;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getExecution(): ?\DateTimeInterface
    {
        return $this->execution;
    }

    public function setExecution(\DateTimeInterface $execution): self
    {
        $this->execution = $execution;

        return $this;
    }

}
