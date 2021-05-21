<?php

namespace App\Controller\Support;


use App\Controller\AbstractApiController;
use App\Model\CommerceTraitModel;
use App\Model\SupportTraitModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class CommerceController
 * @TODO Ensure that the error throws properly to API
 * @IsGranted("ROLE_USER")
 * @package App\Controller
 */
abstract class AbstractSupportApiController extends AbstractApiController
{

    use SupportTraitModel;

    public function __construct()
    {
        if (!$this->isEntityModuleEnabled())
        {
            throw new ResourceNotFoundException("The requested route belongs to \"" . $this->getBaseModule() . "\" and it is currently disabled");
        }
    }

}
