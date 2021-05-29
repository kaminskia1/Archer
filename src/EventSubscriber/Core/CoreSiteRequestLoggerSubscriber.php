<?php
namespace App\EventSubscriber\Core;

use App\Entity\Logger\LoggerSiteRequest;
use App\Model\CoreTraitModel;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ErrorException;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Core\Security;

class CoreSiteRequestLoggerSubscriber implements EventSubscriberInterface
{

    use CoreTraitModel;

    private $security;

    public function __construct( EntityManagerInterface $entityManager )
    {
        $this->entityManager = $entityManager;
    }
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $log = new LoggerSiteRequest($event->getRequest());

        //$this->entityManager->persist($log);

    }
}