<?php
namespace App\EventSubscriber\Commerce;

use App\Entity\Commerce\CommerceInvoice;
use App\Model\CommerceTraitModel;
use DateInterval;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommerceInvoiceCrudSubscriber implements EventSubscriberInterface
{

    use CommerceTraitModel;

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityUpdatedEvent::class => ['BeforeEntityUpdatedEvent'],
            BeforeEntityPersistedEvent::class => ['BeforeEntityPersistedEvent'],
        ];
    }

    public function BeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof CommerceInvoice))
        {
            return;
        }

        $entity->setCommerceGatewayType(
            $entity->getCommerceGatewayInstance()
                ->getCommerceGatewayType()
        );
    }

    public function BeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof CommerceInvoice))
        {
            return;
        }

        $entity->setCommerceGatewayType(
            $entity->getCommerceGatewayInstance()
                ->getCommerceGatewayType()
        );
    }

}