<?php

namespace App\EventSubscriber\Core;

use App\Entity\Core\CoreModule;
use App\Entity\Logger\LoggerSiteRequest;
use App\Model\CoreTraitModel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class CoreSiteRequestLoggerSubscriber implements EventSubscriberInterface
{

    use CoreTraitModel;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
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
        if ($this->entityManager->getRepository(CoreModule::class)->isModuleLoaded('Logger')) {
            if ($this->entityManager->isOpen())
            {
                $log = new LoggerSiteRequest($event->getRequest());
                $this->entityManager->persist($log);
                $this->entityManager->flush();
            }
        }
    }

}