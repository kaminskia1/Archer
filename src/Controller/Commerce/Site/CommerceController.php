<?php

namespace App\Controller\Commerce\Site;

use App\Controller\Commerce\AbstractCommerceController;
use App\Entity\Commerce\CommerceGatewayInstance;
use App\Entity\Commerce\CommerceInvoice;
use App\Entity\Commerce\CommercePackage;
use App\Entity\Commerce\CommercePackageGroup;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use App\Form\Commerce\CommerceCheckoutFormType;
use App\Model\CommerceTraitModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommerceController
 * @TODO Seperate into multiple files
 * @IsGranted("ROLE_USER")
 * @package App\Controller
 */
class CommerceController extends AbstractCommerceController
{

    use CommerceTraitModel;


    /**
     * @Route("/store", name="app_commerce_store")
     */
    public function storeRenderGroups(): Response
    {
        $items = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(CommercePackageGroup::class)
            ->findAll();

        return $this->render('module/commerce/store.html.twig', [
            'title' => 'Store',
            'template' => 'module/commerce/group_short.html.twig',
            'items' => $items,
            'name' => 'store',
            'productCount' => count($items)
        ]);
    }

    /**
     * @Route("/store/group/{gid}", name="app_commerce_store_group")
     *
     * @param int $gid
     * @return Response
     */
    public function storeRenderGroupProducts(int $gid): Response
    {
        $items = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(CommercePackageGroup::class)
            ->find($gid)
            ->getCommercePackage();

        $count = count($items);
        foreach ($items as $item) {
            if (!$item->getIsVisible() || !$item->getIsEnabled()) {
                $count -= 1;
            }
        }

        return $this->render('module/commerce/store.html.twig', [
            'title' => 'Store',
            'template' => 'module/commerce/package_short.html.twig',
            'items' => $items,
            'name' => 'store',
            'productCount' => $count
        ]);
    }


    /**
     * @Route("/store/package/{pid}", name="app_commerce_package")
     *
     * @param int $pid
     * @return Response
     */
    public function storePackage(int $pid): Response
    {
        // get package
        $package = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(CommercePackage::class)
            ->find($pid);

        // create invoice and bind product
        $invoice = new CommerceInvoice();
        $invoice->setCommercePackage($package);

        // create form, will redirect to checkout (app_commerce_checkout) when submitted
        $form = $this->createForm(CommerceCheckoutFormType::class, $invoice, ['form_stage' => 0, 'action' => '/store/checkout']);

        return $this->render('module/commerce/package.html.twig', [
            'title' => 'Store',
            'name' => 'store',
            'package' => $package,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/store/checkout", name="app_commerce_checkout")
     *
     * @param Request $r
     * @return Response
     */
    public function storeCheckout(Request $r): Response
    {
        /**
         * @TODO Ensure package, packageDTP value exist are valid and are active (isActive). If not, throw error (XSS?)
         * @TODO Overhaul to properly utilize cross-page form data
         * (this is a dumpster fire)
         */

        $invoice = new CommerceInvoice();
        $entityManager = $this->getDoctrine()->getManager();
        $ikey = $r->get('commerce_checkout_form')['commerceGatewayInstance'] ?? null;
        if (!is_null($ikey)) {
            $stage = 2;
            $instance = $entityManager
                ->getRepository(CommerceGatewayInstance::class)
                ->find((int)$ikey);

            $invoice->setCommerceGatewayInstance($instance);
        }


        $form = $this->createForm(CommerceCheckoutFormType::class, $invoice, ['form_stage' => $stage ?? 1]);

        $form->handleRequest($r);

        /**
         * @var CommercePackage $package
         */
        $package = $form->getData()->getCommercePackage();
        $dtp = $form->getData()->getCommercePackageDurationToPriceID();

        // Verify that valid package and duration to price id provided
        if (!$package || is_null($dtp))
        {
            return $this->render('module/core/error/400.html.twig');
        }

        // Verify that package is enabled and duration to price exists
        if (!$package->getIsEnabled() && isset($package->getDurationToPrice()[$dtp]))
        {
            return $this->render('module/core/error/400.html.twig');
        }

        // Get corresponding duration and price
        list($duration, $price) = explode(":", $package->getDurationToPrice()[$dtp]);

        // Bind price and duration to invoice
        $invoice->setPrice($price);
        $invoice->setDurationDateInterval(date_interval_create_from_date_string($duration . " days"));


        /**
         * Begin secondary stage of checkout (Process as if page is "submitted"
         */
        // Build invoice and run gateway redirect
        if ($form->isSubmitted() && $form->isValid() && $form->offsetExists('confirm') && $form->get('confirm') == true) {


            // @TODO: Implement discount codes
            //$invoice->setDiscountCode();

            $invoice->setCommerceGatewayType($invoice->getCommerceGatewayInstance()->getCommerceGatewayType());
            $invoice->setUser($this->getUser());

            $gatewayData = [];
            $fields = $invoice
                ->getCommerceGatewayType()
                ->getClassInstance()
                ->getFormFields();

            foreach ($fields as $element) {
                if (
                    $form->offsetExists('gateway__' . $element->title)
                    && $data = $form->get('gateway__' . $element->title)->getData()
                ) {
                    $gatewayData[$element->title] = $data;
                } else {
                    return $this->render('module/commerce/checkout.html.twig', [
                        'title' => 'Checkout',
                        'name' => 'checkout',
                        'invoice' => $invoice,
                        'currency' => $_ENV['COMMERCE_CURRENCY'],
                        'form' => $form->createView()
                    ]);
                }
            }

            // Save the invoice
            $entityManager->persist($invoice);
            $entityManager->flush();

            // Handle the redirect
            $response = $invoice
                ->getCommerceGatewayInstance()
                ->getCommerceGatewayType()
                ->getClassInstance()
                ->handleRedirect(

                    // Invoice object
                    $invoice,

                    // User payment-complete callback url
                    $this->generateUrl('app_commerce_checkout_complete', [
                        'id' => $invoice->getId()
                    ]),

                    // Custom gateway data
                    $gatewayData
                );

            // Save any pass-by-ref changes to the invoice
            $entityManager->flush();

            // Return the handled redirect
            return $response;
        }

        return $this->render('module/commerce/checkout.html.twig', [
            'title' => 'Checkout',
            'name' => 'checkout',
            'invoice' => $invoice,
            'currency' => $_ENV['COMMERCE_CURRENCY'],
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/checkout/complete/{id}", name="app_commerce_checkout_complete")
     *
     * @param int $id
     * @return Response
     */
    public function checkoutComplete(int $id)
    {
        $invoice = $this
            ->getDoctrine()
            ->getRepository(CommerceInvoice::class)
            ->findOneBy(['user' => $this->getUser(), 'id' => $id]);

        if (!$invoice) {
            return $this->render("module/core/error/404.html.twig");
        }

        return $this->render("module/commerce/checkout_complete.html.twig", [
            'title' => 'Checkout',
            'name' => 'checkout',
            'invoice' => $invoice
        ]);
    }


    /**
     * @Route("/invoice/{id}", name="app_commerce_invoice_view")
     *
     * @param int $id
     * @return Response
     */
    public function viewInvoice(int $id)
    {
        $invoice = $this
            ->getDoctrine()
            ->getRepository(CommerceInvoice::class)
            ->findOneBy(['user' => $this->getUser(), 'id' => $id]);

        if (!$invoice) {
            return $this->render("module/core/error/404.html.twig");
        }

        return $this->render("module/commerce/invoice_view.html.twig", [
            'title' => 'Invoice',
            'invoice' => $invoice
        ]);
    }

}
