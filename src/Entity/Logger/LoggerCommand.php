<?php

namespace App\Entity\Logger;

use App\Command\AbstractArcherCommand;
use App\Repository\Logger\LoggerCommandRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoggerCommandRepository::class)
 */
class LoggerCommand
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
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $execution;

    /**
     * LoggerCommand constructor.
     *
     * @param AbstractArcherCommand $command
     */
    public function __construct(AbstractArcherCommand $command)
    {
        $this->name = $command->logName;
        $this->execution = new DateTime('now');
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
     * Get name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get execution datetime
     *
     * @return \DateTimeInterface|null
     */
    public function getExecution(): ?\DateTimeInterface
    {
        return $this->execution;
    }

    /**
     * Set execution datetime
     *
     * @param \DateTimeInterface $execution
     * @return $this
     */
    public function setExecution(\DateTimeInterface $execution): self
    {
        $this->execution = $execution;

        return $this;
    }
}
