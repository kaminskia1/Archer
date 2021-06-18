<?php
namespace App\EventSubscriber\Core;

use App\Model\CoreTraitModel;
use App\Module\Core\CorePasswordHasher;
use DateTime;
use App\Entity\Core\CoreUser;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CoreUserCrudSubscriber implements EventSubscriberInterface
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
            BeforeEntityUpdatedEvent::class => ['BeforeEntityUpdatedEvent'],
            BeforeEntityPersistedEvent::class => ['BeforeEntityPersistedEvent'],
        ];
    }

    public function BeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof CoreUser))
        {
            return;
        }

        $entity->setGroups($entity->getGroups());
        $this->_encodePassword( $entity );

        return;
    }

    public function BeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof CoreUser))
        {
            return;
        }

        $this->_encodePassword( $entity );
        $entity->__populate();

        $code = $entity->getRegistrationCode();
        $code->setEnabled(false);
        $code->setUsageDate(new DateTime());
        $code->setUsedBy( $entity );

        $entity->setGroups($entity->getGroups());
        $entity->setStaffNote( $entity->getStaffNote() ?? "" );
        $entity->setNickname( $entity->getNickname() ?? "" );
    }

    public function _encodePassword(CoreUser &$entity)
    {
        if ( strlen($entity->getPlainPassword()) > 0 )
        {
            // encode the plain password
            $entity->setPassword(
                $this->passwordEncoder->encodePassword(
                    $entity,
                    CorePasswordHasher::hashPassword($entity->getPlainPassword())
                )
            );

            // peace-of-mind "" vs "NULL"
            $entity->setPlainPassword(null);
        }
    }

}