<?php

namespace App\Controller\Commerce\Api;

use App\Controller\AbstractApiController;
use App\Entity\Commerce\CommerceInvoice;
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

class CheckoutCallbackController extends AbstractApiController
{
    /**
     * Commerce checkout callback handler
     *
     * @Rest\View
     * @Rest\Post("/api/commerce/callback", name="api_commerce_checkout_callback")
     * @Rest\RequestParam(name="id")
     *
     * @param Request $request
     * @return Response
     */
    public function index( Request $request ): Response
    {

        $entityManager = $this
            ->getDoctrine()
            ->getManager();

        $invoice = $entityManager
            ->getRepository(CommerceInvoice::class)
            ->find($request->get('id'));

        if ($invoice == null)
        {
            // return pay state error
            return $this->handleView( $this->view([
                'success' => false,
                'message' => 'Invalid Invoice'
            ], Response::HTTP_NOT_FOUND
            )->setFormat('json'));
        }

        if ($invoice->getPaymentState() != CommerceInvoicePaymentStateEnum::INVOICE_OPEN)
        {
            // return pay state error
            return $this->handleView( $this->view([
                'success' => false,
                'message' => 'Invalid Invoice'
            ], Response::HTTP_NOT_FOUND,
            )->setFormat('json'));
        }


        $handle = $invoice->getCommerceGatewayInstance()->getCommerceGatewayType()->getClass()::handleCallback( $invoice, $entityManager );
        return $this->handleView( $this->view([
            'success' => $handle[0],
            'message' => $handle[1],
            'data' => $handle[2] ?? null
        ], Response::HTTP_OK,
        )->setFormat('json'));
    }
}
