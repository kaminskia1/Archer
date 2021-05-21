<?php

namespace App\Controller\Core\Site;

use App\Controller\Core\AbstractCoreController;
use App\Model\CoreTraitModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 *
 * Class ClientViewController
 * @package App\Controller\Client
 * @IsGranted("ROLE_USER")
 */
class ClientViewController extends AbstractCoreController
{
    use CoreTraitModel;


    /**
     * @Route("/client", name="app_dashboard_client")
     */
    public function index(): Response
    {
        return $this->render("module/core/client/client.html.twig", ['title'=>'Client']);
    }


    /**
     * @Route("/terms", name="app_terms")
     *
     * @return Response
     */
    public function terms(): Response
    {
        return $this->render("module/core/landing/terms.html.twig", ['title'=>'Terms']);
    }

    /**
     * @Route("/privacy", name="app_privacy")
     *
     * @return Response
     */
    public function privacy(): Response
    {
        return $this->render("module/core/landing/privacy.html.twig", ['title'=>'Privacy']);
    }
}
