<?php

namespace App\Controller\Core\Api;

use App\Controller\AbstractApiController;
use App\Entity\Commerce\CommerceInvoice;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use App\Model\CoreTraitModel;
use App\Repository\Commerce\CommerceInvoiceRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAuthController extends AbstractApiController
{
    use CoreTraitModel;

    /**
     * CoreUser Packages
     *
     * @Rest\View
     * @Rest\Post("/api/core/auth", name="api_core_user_auth")
     * @Rest\RequestParam(name="username")
     * @Rest\RequestParam(name="password")
     *
     * @TODO Finish this
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $username = $request->get( 'username' );
        $password = $request->get( 'password' );

    }
}