<?php

namespace App\Module\Commerce;

use App\Entity\Commerce\CommerceInvoice;
use App\Entity\Commerce\CommercePurchase;
use App\Entity\Commerce\CommerceTransaction;
use App\Entity\Commerce\CommerceUserSubscription;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use Doctrine\DBAL\Schema\AbstractAsset;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

abstract class GatewayType extends AbstractController
{

    abstract public function getFormFields(): array;

    abstract public function getInstanceOptions(): array;

    abstract public function handleCallback(CommerceInvoice &$invoice, EntityManager $entityManager): array;

    abstract public function handleRedirect(CommerceInvoice &$invoice, string $finalUrl, array $gatewayFormData): RedirectResponse;

    abstract public function getManualOnly();

    public static function createPST(CommerceInvoice $invoice): array
    {

        $entityManager = $GLOBALS['kernel']->getContainer()->get('doctrine.orm.entity_manager');
        $purchase = new CommercePurchase( $invoice );
        $transaction = new CommerceTransaction( $invoice, $purchase );

        if ( $entityManager->getRepository(CommerceUserSubscription::class)->checkIfPreexisting( $purchase ))
        {
            $subscription = $entityManager->getRepository(CommerceUserSubscription::class)->getByPurchase( $purchase );
        }
        else
        {
            $subscription = new CommerceUserSubscription( $invoice );
        }

        return [$purchase, $transaction, $subscription];

    }
}