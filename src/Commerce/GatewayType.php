<?php

namespace App\Commerce;

use App\Entity\CommerceInvoice;
use App\Entity\CommercePurchase;
use App\Entity\CommerceTransaction;
use App\Entity\CommerceUserSubscription;
use App\Enum\CommerceInvoicePaymentStateEnum;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class GatewayType
{
    abstract public function getFormFields(): array;

    abstract public function getInstanceOptions(): array;

    abstract public function handleCallback( CommerceInvoice $invoice, EntityManager $entityManager ): array;

    abstract public function handleRedirect( CommerceInvoice $invoice, array $gatewayFormData ): RedirectResponse;

    public function createPST( CommerceInvoice $invoice, EntityManager $entityManager ): object
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

        return (object)[
            'purchase' => $purchase,
            'transaction' => $transaction,
            'subscription' => $subscription
        ];

    }
}