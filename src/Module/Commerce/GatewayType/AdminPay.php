<?php

namespace App\Module\Commerce\GatewayType;

use App\Entity\Commerce\CommerceInvoice;
use App\Enum\Commerce\CommerceGatewayCallbackResponseEnum;
use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use App\Module\Commerce\GatewayType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

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

    public function handleCallback(CommerceInvoice &$invoice, EntityManager $entityManager, Request $request): array
    {
        if (in_array("ROLE_ADMIN", $invoice->getUser()->getRoles())) {
            $invoice->approve();
            return [CommerceGatewayCallbackResponseEnum::TYPE_SUCCESS, "The invoice has been approved"];
        }
        $invoice->cancel();
        return [CommerceGatewayCallbackResponseEnum::TYPE_FAILURE, "The invoice has been denied"];
    }

    public function handleRedirect(CommerceInvoice &$invoice, string $finalUrl, array $gatewayFormData): RedirectResponse
    {

        $invoice->setPaymentState(CommerceInvoicePaymentStateEnum::INVOICE_PENDING);
        $invoice->setPaymentUrl($GLOBALS['kernel']->getContainer()->get('router')->generate('api_commerce_checkout_callback', ['id' => $invoice->getId()]));
        return new RedirectResponse($finalUrl);
    }
}