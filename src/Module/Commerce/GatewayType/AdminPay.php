<?php

namespace App\Module\Commerce\GatewayType;

use App\Entity\Commerce\CommerceInvoice;
use App\Enum\Commerce\CommerceGatewayCallbackResponseEnum;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use App\Module\Commerce\GatewayType;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminPay extends GatewayType
{

    /**
     * Whether this gateway requires manual approval
     *
     * @var bool
     */
    public static $manualOnly = false;

    /**
     * Get manual only
     *
     * @return bool
     */
    public function getManualOnly()
    {
        return self::$manualOnly;
    }

    public function getFormFields(): array
    {
        return [];
    }

    public function getInstanceOptions(): array
    {
        return [];
    }

    public function handleCallback(CommerceInvoice &$invoice, EntityManager $entityManager): array
    {
        $invoice->setPaymentState(CommerceInvoicePaymentStateEnum::INVOICE_PAID);
        $invoice->setPaidOn(new DateTime());

        list($purchase, $transaction, $subscription) = parent::createPST($invoice);

        $subscription->_addTime($purchase->getDuration());

        $entityManager->persist($invoice);
        $entityManager->persist($purchase);
        $entityManager->persist($transaction);
        $entityManager->persist($subscription);
        $entityManager->flush();

        return [CommerceGatewayCallbackResponseEnum::TYPE_SUCCESS, []];
    }

    public function handleRedirect(CommerceInvoice &$invoice, string $finalUrl, array $gatewayFormData): RedirectResponse
    {

        $invoice->setPaymentState(CommerceInvoicePaymentStateEnum::INVOICE_PENDING);
        $invoice->setPaymentUrl($GLOBALS['kernel']->get('router')->generate('api_commerce_checkout_callback', ['id' => $invoice->getId()]));
        return new RedirectResponse($finalUrl);
    }
}