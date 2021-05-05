<?php
namespace App\EventSubscriber\Core;

use DateTime;
use App\Entity\Core\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CoreUserCrudSubscriber implements EventSubscriberInterface
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

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

        if (!($entity instanceof User))
        {
            return;
        }

        $this->_encodePassword( $entity );
        $entity->setStaffNote( $entity->getStaffNote() ?? "" );
        $entity->setNickname( $entity->getNickname() ?? "" );

        return;
    }

    public function BeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User))
        {
            return;
        }

        $this->_encodePassword( $entity );
        $entity->__populate();

        $code = $entity->getRegistrationCode();
        $code->setEnabled(false);
        $code->setUsageDate(new DateTime());
        $code->setUsedBy( $entity );

        $entity->setStaffNote( $entity->getStaffNote() ?? "" );
        $entity->setNickname( $entity->getNickname() ?? "" );
    }

    public function _encodePassword( User &$entity)
    {
        if ( strlen($entity->getPlainPassword()) > 0 )
        {
            // encode the plain password
            $entity->setPassword(
                $this->passwordEncoder->encodePassword(
                    $entity,
                    $entity->getPlainPassword()
                )
            );

            // peace-of-mind "" vs "NULL"
            $entity->setPlainPassword(null);
        }
    }

}