<?php

namespace App\Controller\Commerce\Api;

use App\Controller\Commerce\AbstractCommerceApiController;
use App\Entity\Commerce\CommerceInvoice;
use App\Model\CommerceTraitModel;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckoutCallbackController extends AbstractCommerceApiController
{

    use CommerceTraitModel;


    /**
     * Commerce checkout callback handler
     *
     * @Rest\View
     * @Rest\Post("/api/commerce/callback", name="api_commerce_checkout_callback")
     * @Rest\Get("/api/commerce/callback", name="api_commerce_checkout_callback")
     * @Rest\RequestParam(name="id")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        // Grab EntityManager
        $entityManager = $this
            ->getDoctrine()
            ->getManager();

        // Grab invoice
        $invoice = $entityManager
            ->getRepository(CommerceInvoice::class)
            ->find($request->get('id'));

        // Check that invoice exists
        if (!$invoice) {
            // return pay state error
            return $this->handleView($this->view([
                'success' => false,
                'message' => 'Invalid Invoice'
            ], Response::HTTP_NOT_FOUND
            )->setFormat('json'));
        }

        // Ensure that it is open
        if (!($invoice->isOpen() || $invoice->isPending())) {
            // return pay state error
            return $this->handleView($this->view([
                'success' => false,
                'message' => 'Invalid Invoice'
            ], Response::HTTP_NOT_FOUND,
            )->setFormat('json'));
        }

        // Check if manual approval required
        if ($invoice
            ->getCommerceGatewayInstance()
            ->getCommerceGatewayType()
            ->getClassInstance()
            ->getManualOnly()) {
            // return pay state error
            return $this->handleView($this->view([
                'success' => false,
                'message' => 'This invoice must be approved manually'
            ], Response::HTTP_FORBIDDEN,
            )->setFormat('json'));
        }


        $handle = $invoice
            ->getCommerceGatewayInstance()
            ->getCommerceGatewayType()
            ->getClassInstance()
            ->handleCallback($invoice, $entityManager, $request);

        return $this->handleView($this->view([
            'success' => $handle[0],
            'message' => $handle[1],
            'data' => $handle[2] ?? null
        ], Response::HTTP_OK,
        )->setFormat('json'));
    }
}
