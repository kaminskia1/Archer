<?php

namespace App\Controller\Core\Site;

use App\Controller\Core\AbstractCoreController;
use App\Entity\Core\CoreUser;
use App\Model\CoreTraitModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 *
 * Class UserAccountController
 * @package App\Controller\Client
 * @IsGranted("ROLE_USER")
 */
class UserAccountController extends AbstractCoreController
{
    use CoreTraitModel;


    /**
     * @Route("/my-account", name="app_dashboard_account")
     */
    public function index(): Response
    {
        /**
         * @var CoreUser $user
         */
        $user = $this->getUser();
        return $this->render("module/core/account/index.html.twig", [
            'title' => 'My Account',
            'name' => 'account',
            'user' => $user]);
    }

}