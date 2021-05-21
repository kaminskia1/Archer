<?php

namespace App\Module\Commerce\GatewayType;

use App\Enum\Commerce\CommerceInvoicePaymentStateEnum;
use App\Module\Commerce\GatewayType;
use App\Entity\Commerce\CommerceInvoice;
use App\Enum\Commerce\CommerceGatewayCallbackResponseEnum;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class _SamplePay extends GatewayType
{

    /**
     * Whether this gateway supports payment by the user at a later time
     *
     * @var bool
     */
    public static $manualOnly = true;


    /**
     * Get manual only
     *
     * @return bool
     */
    public function getManualOnly()
    {
        return self::$manualOnly;
    }

    /**
     * Get applicable checkout form fields
     *
     * @return object[]
     */
    public function getFormFields(): array
    {
        // Returns custom checkout form fields
        return [
            (object)[
                'title'=> 'Sample',
                'type'=> TextType::class,
                'options'=>[]
            ]
        ];
    }

    /**
     * Get instance-specific options
     *
     * @return array
     */
    public function getInstanceOptions(): array
    {
        // Returns instance-specific options
        return [];
    }

    /**
     * Handle a callback from a third-party source (purchasing afterwork, secret validation, etc)
     *
     * @param CommerceInvoice $invoice
     * @param EntityManager $entityManager
     * @return array
     */
    public function handleCallback(CommerceInvoice &$invoice, EntityManager $entityManager ): array
    {
        // Handles callback from payment processor
        return [CommerceGatewayCallbackResponseEnum::TYPE_FAILURE, []];
    }

    /**
     * Where to redirect the user to after the checkout flow is completed
     *
     * @param CommerceInvoice $invoice
     * @param string $finalUrl
     * @param array $gatewayFormData
     * @return RedirectResponse
     */
    public function handleRedirect(CommerceInvoice &$invoice, string $finalUrl, array $gatewayFormData ): RedirectResponse
    {
        // Redirects user to payment processor
        $invoice->setPaymentState(CommerceInvoicePaymentStateEnum::INVOICE_PENDING);
        $invoice->setPaymentUrl($finalUrl);
        return new RedirectResponse($finalUrl);
    }
}