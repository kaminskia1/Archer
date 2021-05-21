<?php

namespace App\Controller\Linker;


use App\Model\CommerceTraitModel;
use App\Model\IRCTraitModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class CommerceController
 * @IsGranted("ROLE_ADMIN")
 * @package App\Controller
 */
abstract class AbstractLinkerController extends AbstractController
{

    use IRCTraitModel;

    public function __construct()
    {
        if (!$this->isEntityModuleEnabled())
        {
            throw new ResourceNotFoundException("The requested route belongs to \"" . $this->getBaseModule() . "\" and it is currently disabled");
        }
    }
}
