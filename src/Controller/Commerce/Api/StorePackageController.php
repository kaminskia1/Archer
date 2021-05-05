<?php

namespace App\Controller\Commerce\Api;

use App\Controller\AbstractApiController;
use App\Entity\Commerce\CommerceInvoice;
use App\Entity\Commerce\CommercePackage;
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

class StorePackageController extends AbstractApiController
{
    /**
     * List Store Packages
     *
     * @Rest\View
     * @Rest\Get("/api/commerce/packages", name="api_commerce_store_packages")
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $raw = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(CommercePackage::class)
            ->findAll();

        $new = [];
        foreach ($raw as $val)
        {
            array_push($new, [
                'id' => $val->getId(),
                'name' => $val->getName(),
                'stock' => $val->getStock(),
                'isEnabled' => $val->getIsEnabled(),
                'isVisible' => $val->getIsVisible(),
                'group' => $val->getCommercePackageGroup()->getName(),
                'description' => $val->getStoreDescription()
            ]);
        }

        return $this->handleView( $this->view([
            'success' => true,
            'data' => $new
        ], Response::HTTP_OK
        )->setFormat('json'));
    }

    /**
     * List Store Packages
     *
     * @Rest\View
     * @Rest\Get("/api/commerce/packages/{$id}", name="api_commerce_store_packages_id")
     *
     * @param Request $request
     * @return Response
     */
    public function package(Request $request, int $id)
    {
        $val = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(CommercePackage::class)
            ->findOneBy(['id' => $id]);

        if ($val == null)
        {
            return $this->handleView( $this->view([
                'success' => false,
                'message' => 'Invalid package'
            ], Response::HTTP_NOT_FOUND
            )->setFormat('json'));
        }


        return $this->handleView( $this->view([
            'success' => true,
            'data' => [
                'id' => $val->getId(),
                'name' => $val->getName(),
                'stock' => $val->getStock(),
                'isEnabled' => $val->getIsEnabled(),
                'isVisible' => $val->getIsVisible(),
                'group' => $val->getCommercePackageGroup()->getName(),
                'description' => $val->getStoreDescription()
            ]
        ], Response::HTTP_OK
        )->setFormat('json'));
    }
}