<?php

namespace App\Controller\Core\Site;

use App\Controller\Core\AbstractCoreController;
use App\Model\CoreTraitModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class LandingController extends AbstractCoreController
{
    use CoreTraitModel;

    private $security;

    public function __construct(Security $security)
    {
     $this->security = $security;
    }

    /**
     * @Route("/", name="landing")
     */
    public function index(): Response
    {
        if ($this->security->isGranted("ROLE_ADMIN"))
        {
            return $this->redirectToRoute('app_dashboard_admin');
        }

        if ($this->security->isGranted("ROLE_MODERATOR"))
        {
            return $this->redirectToRoute('app_dashboard_moderator');
        }

        if ($this->security->isGranted("ROLE_SELLER"))
        {
            return $this->redirectToRoute('app_dashboard_seller');
        }

        if ($this->security->isGranted("ROLE_USER"))
        {
            return $this->redirectToRoute('app_dashboard_client');
        }

        return $this->redirectToRoute('app_login');

    }

}
