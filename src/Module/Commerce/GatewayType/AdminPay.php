<?php

namespace App\Module\Commerce\GatewayType;

use App\Module\Commerce\GatewayType;
use App\Entity\Commerce\CommerceInvoice;
use App\Enum\Commerce\CommerceGatewayCallbackResponseEnum;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminPay extends GatewayType
{
    public function getFormFields(): array
    {
        return [];
    }

    public function getInstanceOptions(): array
    {
        return [];
    }

    public function handleCallback(CommerceInvoice $invoice, EntityManager $entityManager ): array
    {
        $invoice->setPaymentState( CommerceInvoicePaymentStateEnum::INVOICE_PAID );
        $invoice->setPaidOn( new \DateTime() );

        list($purchase, $transaction, $subscription) = parent::createPST( $invoice, $entityManager );

        $subscription->_addTime( $purchase->getDuration() );

        $entityManager->persist( $invoice );
        $entityManager->persist( $purchase );
        $entityManager->persist( $transaction );
        $entityManager->persist( $subscription );
        $entityManager->flush();

        return [CommerceGatewayCallbackResponseEnum::TYPE_SUCCESS, []];
    }

    public function handleRedirect(CommerceInvoice $invoice, array $gatewayFormData): RedirectResponse
    {
        return new RedirectResponse("http://localhost:8000/store?invoice_id=" . $invoice->getId());
    }
}