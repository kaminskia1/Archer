<?php

namespace App\Controller\Core;


use App\Controller\AbstractApiController;
use App\Model\CoreTraitModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class CommerceController
 * @IsGranted("ROLE_USER")
 * @package App\Controller
 */
abstract class AbstractCoreApiController extends AbstractApiController
{

    use CoreTraitModel;


    public function __construct()
    {

    }

}
