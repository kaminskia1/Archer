<?php

namespace App\Controller\IRC;


use App\Controller\AbstractApiController;
use App\Model\CommerceTraitModel;
use App\Model\IRCTraitModel;
use App\Model\LinkerTraitModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class CommerceController
 * @TODO Ensure that the error throws properly to API
 * @IsGranted("ROLE_USER")
 * @package App\Controller
 */
abstract class AbstractIRCApiController extends AbstractApiController
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
