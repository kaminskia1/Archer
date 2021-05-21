<?php

namespace App\Controller\Core\Site;

use App\Entity\Commerce\CommerceUserSubscription;
use App\Entity\Core\CoreUser;
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

        /**
         * @var CoreUser $user
         */
        $user = $this->getUser();

        $subscription = $this->getDoctrine()->getRepository(CommerceUserSubscription::class)->findBy(['user' => $user, 'id' => 2]);

        dump($subscription);

        return $this->render('base.html.twig');

    }
}
