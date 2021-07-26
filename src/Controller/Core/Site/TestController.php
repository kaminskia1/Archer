<?php

namespace App\Controller\Core\Site;

use App\Entity\Commerce\CommerceUserSubscription;
use App\Entity\Core\CoreUser;
use App\Model\CoreTraitModel;
use App\Controller\Core\AbstractCoreController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV1;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Uid\UuidV6;

class TestController extends AbstractCoreController
{

    use CoreTraitModel;

    /**
     * @Route("/test", name="test")
     */
    public function index()
    {

        /**
         * @var CoreUser $user
         */
        $user = $this->getUser();



        return $this->render('base.html.twig');

    }
}
