<?php

namespace App\Controller\Api\Commerce;

use App\Entity\CommerceInvoice;
use App\Enum\CommerceInvoicePaymentStateEnum;
use App\Repository\CommerceInvoiceRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutCallbackController extends AbstractFOSRestController
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
    public function checkoutCallback( Request $request ): Response
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
            ], 404
            )->setFormat('json'));
        }

        if ($invoice->getPaymentState() != CommerceInvoicePaymentStateEnum::INVOICE_OPEN)
        {
            // return pay state error
            return $this->handleView( $this->view([
                'success' => false,
                'message' => 'Invalid Invoice'
            ], 405,
            )->setFormat('json'));
        }


        $handle = $invoice->getCommerceGatewayInstance()->getCommerceGatewayType()->getClass()::handleCallback( $invoice, $entityManager );
        return $this->handleView( $this->view([
            'success' => $handle[0],
            'message' => $handle[1],
            'data' => $handle[2] ?? null
        ], 405,
        )->setFormat('json'));
    }
}
