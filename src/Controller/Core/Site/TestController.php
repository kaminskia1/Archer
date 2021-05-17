<?php

namespace App\Controller\Core\Site;

use App\Model\CoreTraitModel;
use App\Controller\Core\AbstractCoreController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractCoreController
{

    use CoreTraitModel;

    /**
     * @Route("/test", name="test")
     */
    public function index()
    {

        return $this->render('base.html.twig');

    }
}
