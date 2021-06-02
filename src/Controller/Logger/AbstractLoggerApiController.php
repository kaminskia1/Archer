<?php

namespace App\Controller\Logger;


use App\Controller\AbstractApiController;
use App\Model\LoggerTraitModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class CommerceController
 * @TODO Ensure that the error throws properly to API
 * @IsGranted("ROLE_ADMIN")
 * @package App\Controller
 */
abstract class AbstractLoggerApiController extends AbstractApiController
{

    use LoggerTraitModel;

    public function __construct()
    {
        if (!$this->isEntityModuleEnabled()) {
            throw new ResourceNotFoundException("The requested route belongs to \"" . $this->getBaseModule() . "\" and it is currently disabled");
        }
        parent::__construct();
    }

}
