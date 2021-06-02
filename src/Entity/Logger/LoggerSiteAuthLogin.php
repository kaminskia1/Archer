<?php

namespace App\Entity\Logger;

use App\Entity\Core\CoreModule;
use App\Entity\Core\CoreUser;
use App\Repository\Logger\LoggerSiteAuthLoginRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

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

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=46, nullable=true)
     */
    private $ip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $basePath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userAgent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $language;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isUserReal;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAuthSuccessful;

    /**
     * @ORM\Column(type="datetime")
     */
    private $execution;

    /**
     * LoggerSiteAuthLogin constructor.
     *
     * @param Request $request
     * @param AuthenticationException|TokenInterface $type
     */
    public function __construct(Request $request, /* AuthenticationException|TokenInterface */ $type)
    {
        $this->ip = $request->getClientIp();
        $this->basePath = $request->getRequestUri();
        $this->userAgent = $request->headers->get('User-Agent');
        $this->language = $request->getDefaultLocale();
        $this->execution = new DateTime('now');

        if ($type instanceof TokenInterface)
        {
            $this->uuid = $type->getUsername();
            $this->isAuthSuccessful = true;
            $this->isUserReal = true;

        } elseif ($type instanceof AuthenticationException)
        {
            $this->uuid = $type->getToken()->getCredentials()['uuid'];
            $this->isAuthSuccessful = false;
            $this->isUserReal = $GLOBALS['kernel']
                ->getContainer()
                ->get('doctrine.orm.entity_manager')
                ->getRepository(CoreUser::class)
                ->findOneBy(['uuid' => $this->uuid]) instanceof CoreUser;
        }
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

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getBasePath(): ?string
    {
        return $this->basePath;
    }

    public function setBasePath(string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): self
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getIsUserReal(): ?bool
    {
        return $this->isUserReal;
    }

    public function setIsUserReal(bool $isUserReal): self
    {
        $this->isUserReal = $isUserReal;

        return $this;
    }

    public function getIsAuthSuccessful(): ?bool
    {
        return $this->isAuthSuccessful;
    }

    public function setIsAuthSuccessful(bool $isAuthSuccessful): self
    {
        $this->isAuthSuccessful = $isAuthSuccessful;

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
