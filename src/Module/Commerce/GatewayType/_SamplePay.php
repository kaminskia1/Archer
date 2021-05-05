<?php

namespace App\Module\Commerce\GatewayType;

use App\Module\Commerce\GatewayType;
use App\Entity\Commerce\CommerceInvoice;
use App\Enum\Commerce\CommerceGatewayCallbackResponseEnum;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class _SamplePay extends GatewayType
{
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

    public function getInstanceOptions(): array
    {
        // Returns instance-specific options
        return [];
    }

    public function handleCallback( CommerceInvoice $invoice, EntityManager $entityManager ): array
    {
        // Handles callback from payment processor
        return [CommerceGatewayCallbackResponseEnum::TYPE_FAILURE, []];
    }

    public function handleRedirect( CommerceInvoice $invoice, array $gatewayFormData ): RedirectResponse
    {
        // Redirects user to payment processor
        return new RedirectResponse("http://example.com/");
    }
}