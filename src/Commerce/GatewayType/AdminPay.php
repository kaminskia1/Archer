<?php

namespace App\Commerce\GatewayType;

use App\Commerce\GatewayType;
use App\Entity\CommerceInvoice;
use App\Enum\CommerceGatewayCallbackResponseEnum;
use App\Enum\CommerceInvoicePaymentStateEnum;
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

    public function handleCallback( CommerceInvoice $invoice, EntityManager $entityManager ): array
    {
        $invoice->setPaymentState(CommerceInvoicePaymentStateEnum::INVOICE_PAID);
        $invoice->setPaidOn(new \DateTime());

        $data = parent::createPST( $invoice, $entityManager);

        $entityManager->persist($invoice);
        $entityManager->persist($data->purchase);
        $entityManager->persist($data->transaction);

        $data->subscription->_addTime($data->purchase->getDuration());
        $entityManager->persist($data->subscription);

        $entityManager->flush();

        return [CommerceGatewayCallbackResponseEnum::TYPE_SUCCESS, []];
    }

    public function handleRedirect( CommerceInvoice $invoice, array $gatewayFormData): RedirectResponse
    {
        return new RedirectResponse("http://localhost:8000");
    }
}