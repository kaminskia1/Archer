<?php

namespace App\Controller\Logger;


use App\Model\CommerceTraitModel;
use App\Model\IRCTraitModel;
use App\Model\LoggerTraitModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class CommerceController
 * @IsGranted("ROLE_ADMIN")
 * @package App\Controller
 */
abstract class AbstractLoggerController extends AbstractController
{

    use LoggerTraitModel;

    public function __construct()
    {
        if (!$this->isEntityModuleEnabled())
        {
            throw new ResourceNotFoundException("The requested route belongs to \"" . $this->getBaseModule() . "\" and it is currently disabled");
        }
    }
}
