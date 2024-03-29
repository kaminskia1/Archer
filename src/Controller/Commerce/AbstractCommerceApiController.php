<?php

namespace App\Controller\Commerce;


use App\Controller\AbstractApiController;
use App\Model\CommerceTraitModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class CommerceController
 * @TODO Ensure that the error throws properly to API
 * @IsGranted("ROLE_USER")
 * @package App\Controller
 */
abstract class AbstractCommerceApiController extends AbstractApiController
{

    use CommerceTraitModel;


    public function __construct()
    {
        // $this->handleView( $this->view([], Response::HTTP_NOT_FOUND)->setFormat('json'));
        if (!$this->isEntityModuleEnabled())
        {
            throw new ResourceNotFoundException("The requested route belongs to \"" . $this->getBaseModule() . "\" and it is currently disabled");
        }
        parent::__construct();
    }

}
