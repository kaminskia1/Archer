<?php

namespace App\Entity\Logger;

use App\Repository\Logger\LoggerSiteRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;

/**
 * @ORM\Entity(repositoryClass=LoggerSiteRequestRepository::class)
 */
class LoggerSiteRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * LoggerSiteRequest constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {

    }


    public function getId(): ?int
    {
        return $this->id;
    }
}
