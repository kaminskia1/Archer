<?php

namespace App\Entity\Logger;

use App\Repository\Logger\LoggerSiteAuthLoginRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LoggerSiteAuthLoginRepository::class)
 */
class LoggerSiteAuthLogin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
