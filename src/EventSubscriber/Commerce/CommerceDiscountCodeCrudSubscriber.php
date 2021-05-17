<?php
namespace App\EventSubscriber\Commerce;

use App\Entity\Commerce\CommerceDiscountCode;
use App\Model\CommerceTraitModel;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommerceDiscountCodeCrudSubscriber implements EventSubscriberInterface
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

    }

    public function BeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof CommerceDiscountCode))
        {
            return;
        }

        $entity->populate();
    }

}