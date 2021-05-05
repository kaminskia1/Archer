<?php
namespace App\EventSubscriber\Core;

use App\Entity\Core\RegistrationCode;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CoreRegistrationCodeCrudSubscriber implements EventSubscriberInterface
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            // BeforeEntityUpdatedEvent::class => ['BeforeEntityUpdatedEvent'],
            BeforeEntityPersistedEvent::class => ['BeforeEntityPersistedEvent'],
        ];
    }

    public function BeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof RegistrationCode))
        {
            return;
        }

        $entity->setCode( $entity->getCode() ?? md5( random_bytes(10) ) );
        $entity->setEnabled(true);
        $entity->setUsageDate(null);
        $entity->setUsedBy(null);
    }
}