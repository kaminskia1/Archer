<?php

namespace App\Entity\Logger;

use App\Repository\Logger\LoggerSiteRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

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
     * @ORM\Column(type="datetime")
     */
    private $execution;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $route;

    /**
     * @ORM\Column(type="string", length=46, nullable=true)
     */
    private $ip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userAgent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $host;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $method;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $locale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $basePath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $query;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSecure;

    /**
     * LoggerSiteRequest constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->execution = new \DateTime('now');
        $this->route = $GLOBALS['kernel']->getContainer()->get('router')->match($request->getPathInfo())['_route'];
        $this->ip = $request->getClientIp();
        $this->userAgent = $request->headers->get('User-Agent');
        $this->host = $request->getHost();
        $this->method = $request->getMethod();
        $this->locale = $request->getDefaultLocale();
        $this->basePath = $request->getPathInfo();
        $this->query = $request->getQueryString();
        $this->isSecure = $request->isSecure();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): self
    {
        $this->route = $route;

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

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): self
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(?string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getBasePath(): ?string
    {
        return $this->basePath;
    }

    public function setBasePath(?string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(?string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getIsSecure(): ?bool
    {
        return $this->isSecure;
    }

    public function setIsSecure(bool $isSecure): self
    {
        $this->isSecure = $isSecure;

        return $this;
    }
}
