<?php

namespace App\Module\Commerce\GatewayType;

use App\Entity\Commerce\CommerceInvoice;
use App\Enum\Commerce\CommerceGatewayCallbackResponseEnum;
use App\Module\Commerce\GatewayType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ManualPay extends GatewayType
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

    public function getFormFields(): array
    {
        return [
            (object)[
                'title' => 'acceptTermsOfService',
                'type' => CheckboxType::class,
                'options' => [
                    'required' => true,
                    'value' => false,
                    'attr' => ['class' => 'ml-2'],
                    'label' => 'Accept Terms of Service'
                ]
            ]
        ];
    }

    public function getInstanceOptions(): array
    {
        return [];
    }

    public function handleCallback(CommerceInvoice &$invoice, EntityManager $entityManager, Request $request): array
    {
        return [CommerceGatewayCallbackResponseEnum::TYPE_FAILURE, []];
    }

    public function handleRedirect(CommerceInvoice &$invoice, string $finalUrl, array $gatewayFormData): RedirectResponse
    {
        $invoice->setPaymentUrl($finalUrl);
        return new RedirectResponse($finalUrl);
    }

}