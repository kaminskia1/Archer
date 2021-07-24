<?php

namespace App\Controller\Commerce\Site;

use App\Controller\Commerce\AbstractCommerceController;
use App\Model\CommerceTraitModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommerceController
 *
 * @TODO    Require permissions per-method, as they are shared
 * @package App\Controller
 */
class LicenseKeyController extends AbstractCommerceController
{

    use CommerceTraitModel;

    /**
     * @Route("/license/manage/{id}", name="app_commerce_license_manage")
     * @IsGranted("ROLE_SELLER")
     */
    public function storeRenderGroups(int $id): Response
    {
        return new Response("asd");
    }

    /**
     * @Route("/redeem", name="app_commerce_license_redeem")
     */
    public function redeem(): Response
    {
        return new Response("asd");
    }

}
