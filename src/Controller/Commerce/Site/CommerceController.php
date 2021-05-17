<?php

namespace App\Controller\Commerce\Site;

use App\Controller\Commerce\AbstractCommerceController;
use App\Entity\Commerce\CommerceGatewayInstance;
use App\Entity\Commerce\CommerceInvoice;
use App\Entity\Commerce\CommercePackage;
use App\Entity\Commerce\CommercePackageGroup;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use App\Form\CommerceCheckoutFormType;
use App\Model\CommerceTraitModel;
use DateInterval;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Package;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

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

        return $this->render('commerce/store.html.twig', [
            'template' => 'commerce/group_short.html.twig',
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
        foreach ($items as $item)
        {
            if (!$item->getIsVisible() || !$item->getIsEnabled())
            {
                $count -= 1;
            }
        }

        return $this->render('commerce/store.html.twig', [
            'template' => 'commerce/package_short.html.twig',
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
    public function storePackage(int $pid)
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
        $form = $this->createForm(CommerceCheckoutFormType::class, $invoice, ['form_stage' => 0, 'action'=> '/store/checkout']);

        return $this->render('commerce/package.html.twig', [
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
    public function storeCheckout(Request $r)
    {
        /**
         * @TODO Ensure package, packageDTP value exist are valid and are active (isActive). If not, throw error (XSS?)
         * @TODO Overhaul to properly utilize cross-page form data
         * (this is a dumpster fire)
         */

        $invoice = new CommerceInvoice();
        $entityManager = $this->getDoctrine()->getManager();
        $ikey = $r->get('commerce_checkout_form')['commerceGatewayInstance'] ?? null;
        if (!is_null($ikey))
        {
            $stage = 2;
            $instance = $entityManager
                ->getRepository(CommerceGatewayInstance::class)
                ->find((int)$ikey);

            $invoice->setCommerceGatewayInstance($instance);
        }


        $form = $this->createForm(CommerceCheckoutFormType::class, $invoice, ['form_stage' => $stage ?? 1]);

        $form->handleRequest($r);

        $dtp = explode(":", $form->getData()->getCommercePackage()->getDurationToPrice()[$form->getData()->getCommercePackageDurationToPriceID()]);
        $invoice->setPrice($dtp[1]);
        $invoice->setDurationDateInterval(date_interval_create_from_date_string($dtp[0] . " days"));

        // final submit, build invoice and run gateway redirect
        if ($form->isSubmitted() && $form->isValid() && $form->offsetExists('confirm') && $form->get('confirm') == true)
        {
            /**
             * @TODO Verify that Duration and Instance is 100% set, refactor this garbage into something clean
             */
            $data = $form->getData();
            $invoice->setCommerceGatewayType($invoice->getCommerceGatewayInstance()->getCommerceGatewayType());
            // implement in future
            //$invoice->setDiscountCode();
            $invoice->setPaymentState(CommerceInvoicePaymentStateEnum::INVOICE_OPEN);
            $invoice->setUser( $this->getUser() );

            $gatewayData = [];
            $fields = $invoice
                ->getCommerceGatewayType()
                ->__getClassInstance()
                ->getFormFields();
            foreach ($fields as $element)
            {
                if (
                    $form->offsetExists('gateway__' . $element->title)
                    && $data = $form->get('gateway__' . $element->title)->getData()
                )
                {
                    $gatewayData[$element->title] = $data;
                }
                else
                {
                    return $this->render('commerce/checkout.html.twig', [
                        'name' => 'checkout',
                        'invoice' => $invoice,
                        'currency' => $_ENV['COMMERCE_CURRENCY'],
                        'form' => $form->createView()
                    ]);
                }
            }
            $entityManager->persist($invoice);
            $entityManager->flush();
            
            return $invoice->getCommerceGatewayInstance()->getCommerceGatewayType()->getClass()::handleRedirect( $invoice, $gatewayData );
        }

        return $this->render('commerce/checkout.html.twig', [
            'name' => 'checkout',
            'invoice' => $invoice,
            'currency' => $_ENV['COMMERCE_CURRENCY'],
            'form' => $form->createView()
        ]);
    }

}
