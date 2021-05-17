<?php
namespace App\EventSubscriber\Core;

use App\Model\CoreTraitModel;
use ErrorException;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Core\Security;

class CoreUserBannedKernelSubscriber implements EventSubscriberInterface
{

    use CoreTraitModel;

    private $security;

    public function __construct( Security $security )
    {
        $this->security = $security;
    }
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event)
    {
        // debug toolbar messes up if isgranted is called before kernel finishes up
        if ($_ENV['APP_ENV'] != "dev")
        {
            if ($this->security->isGranted("ROLE_BANNED"))
            {
                throw new AccessDeniedException("CoreUser is banned!");
            }
        }
    }
}