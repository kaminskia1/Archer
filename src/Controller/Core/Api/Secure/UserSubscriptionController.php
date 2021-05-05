<?php

namespace App\Controller\Core\Api\Secure;

use App\Controller\AbstractApiController;
use App\Entity\Commerce\CommerceInvoice;
use App\Entity\Commerce\CommerceUserSubscription;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use App\Repository\Commerce\CommerceInvoiceRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserSubscriptionController extends AbstractApiController
{
    /**
     * User Packages
     *
     * @Rest\View
     * @Rest\Get("/api/secure/core/subscriptions", name="api_core_user_subscriptions")
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if ($this->getUser() == null)
        {
            return $this->handleView( $this->view( [
                'success' => false,
                'message' => 'Unauthorized'
            ], Response::HTTP_FORBIDDEN
            )->setFormat('json'));
        }


        $subs = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(CommerceUserSubscription::class)
            ->findBy(['user' => $this->getUser()]);

        $new = [];
        foreach ($subs as $sub )
        {
            array_push($new, [
                'package' => $sub->getCommercePackageAssoc()->getId(),
                'expires' => $sub->getExpiryDateTime(),
            ]);
        }

        return $this->handleView( $this->view( [
                'success' => true,
                'data' => $new
            ], Response::HTTP_OK
        )->setFormat('json'));
    }
}