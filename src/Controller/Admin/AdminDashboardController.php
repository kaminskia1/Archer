<?php

namespace App\Controller\Admin;

use App\Entity\CommerceDiscountCode;
use App\Entity\CommerceGatewayInstance;
use App\Entity\CommerceGatewayType;
use App\Entity\CommerceInvoice;
use App\Entity\CommercePackage;
use App\Entity\CommercePackageGroup;
use App\Entity\CommercePurchase;
use App\Entity\CommerceTransaction;
use App\Entity\CommerceUserSubscription;
use App\Entity\User;
use App\Entity\RegistrationCode;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 *
 * Class AdminDashboardController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class AdminDashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="app_dashboard_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard_landing.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Archer');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Home', 'fa fa-home');
        yield MenuItem::linkToUrl('Client View', 'fa fa-user', $this->generateUrl("app_dashboard_client"));

        yield MenuItem::section('Core');
        yield MenuItem::linkToCrud('Users', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Registration Codes', 'fas fa-list', RegistrationCode::class);

        yield MenuItem::section('Commerce');
        yield MenuItem::linkToCrud('Package Groups', 'fas fa-list', CommercePackageGroup::class);
        yield MenuItem::linkToCrud('Packages', 'fas fa-list', CommercePackage::class);
        yield MenuItem::linkToCrud('Invoices', 'fas fa-list', CommerceInvoice::class);
        yield MenuItem::linkToCrud('Discount Codes', 'fas fa-list', CommerceDiscountCode::class);
        yield MenuItem::linkToCrud('Payment Gateways', 'fas fa-list', CommerceGatewayInstance::class);
        yield MenuItem::linkToCrud('DEBUG: Payment Gateway Types', 'fas fa-list', CommerceGatewayType::class);
        yield MenuItem::linkToCrud('Purchases', 'fas fa-list', CommercePurchase::class);
        yield MenuItem::linkToCrud('User Subscriptions', 'fas fa-list', CommerceUserSubscription::class);
        yield MenuItem::linkToCrud('Transactions', 'fas fa-list', CommerceTransaction::class);
    }
}
