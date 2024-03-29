<?php
namespace App\EventSubscriber\Core;

use App\Entity\Core\CoreRegistrationCode;
use App\Model\CoreTraitModel;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CoreRegistrationCodeCrudSubscriber implements EventSubscriberInterface
{

    use CoreTraitModel;

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

        if (!($entity instanceof CoreRegistrationCode))
        {
            return;
        }

        $entity->setCode( $entity->getCode() ?? md5( random_bytes(10) ) );
        if ($entity->getUsageDate() == null) $entity->setUsageDate(null);
    }
}