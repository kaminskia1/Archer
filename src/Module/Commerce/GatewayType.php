<?php

namespace App\Module\Commerce;

use App\Entity\Commerce\CommerceInvoice;
use App\Entity\Commerce\CommercePurchase;
use App\Entity\Commerce\CommerceTransaction;
use App\Entity\Commerce\CommerceUserSubscription;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class GatewayType
{
    abstract public function getFormFields(): array;

    abstract public function getInstanceOptions(): array;

    abstract public function handleCallback(CommerceInvoice $invoice, EntityManager $entityManager ): array;

    abstract public function handleRedirect(CommerceInvoice $invoice, array $gatewayFormData ): RedirectResponse;

    public function createPST(CommerceInvoice $invoice, EntityManager $entityManager ): array
    {

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