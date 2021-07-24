<?php

namespace App\Module\Commerce;

use App\Entity\Commerce\CommerceInvoice;
use App\Entity\Commerce\CommercePurchase;
use App\Entity\Commerce\CommerceUserSubscription;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class GatewayType extends AbstractController
{

    public static function createPS(CommerceInvoice $invoice): array
    {

        $entityManager = $GLOBALS['kernel']->getContainer()->get('doctrine.orm.entity_manager');
        $purchase = new CommercePurchase($invoice);

        if ($entityManager->getRepository(CommerceUserSubscription::class)->checkIfPreexisting($purchase)) {
            $subscription = $entityManager->getRepository(CommerceUserSubscription::class)->getByPurchase($purchase);
        } else {
            $subscription = new CommerceUserSubscription($invoice);
        }

        return [$purchase, $subscription];

    }

    abstract public function getFormFields(): array;

    abstract public function getInstanceOptions(): array;

    abstract public function handleCallback(CommerceInvoice &$invoice, EntityManager $entityManager, Request $request): array;

    abstract public function handleRedirect(CommerceInvoice &$invoice, string $finalUrl, array $gatewayFormData): RedirectResponse;

    abstract public function getManualOnly();
}