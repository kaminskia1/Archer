<?php
namespace App\EventSubscriber\Commerce;

use App\Entity\Commerce\CommercePackageGroup;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommercePackageGroupCrudSubscriber implements EventSubscriberInterface
{

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

        if (!($entity instanceof CommercePackageGroup))
        {
            return;
        }

        if ( strlen($entity->getImageURI()) < 3) $entity->setImageURI("__default.jpg");
    }

    public function BeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof CommercePackageGroup))
        {
            return;
        }

        if ( strlen($entity->getImageURI()) < 3) $entity->setImageURI("__default.jpg");
    }

}