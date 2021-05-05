<?php

namespace App\Controller\Admin;

use App\Entity\Commerce\CommerceDiscountCode;
use App\Entity\Commerce\CommerceGatewayInstance;
use App\Entity\Commerce\CommerceGatewayType;
use App\Entity\Commerce\CommerceInvoice;
use App\Entity\Commerce\CommercePackage;
use App\Entity\Commerce\CommercePackageGroup;
use App\Entity\Commerce\CommercePurchase;
use App\Entity\Commerce\CommerceTransaction;
use App\Entity\Commerce\CommerceUserSubscription;
use App\Entity\Core\CoreModule;
use App\Entity\Core\User;
use App\Entity\Core\RegistrationCode;

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
        yield MenuItem::linkToCrud('Site Modules', 'fas fa-list', CoreModule::class);

        yield MenuItem::section('Commerce');
        yield MenuItem::linkToCrud('Package Groups', 'fas fa-list', CommercePackageGroup::class);
        yield MenuItem::linkToCrud('Packages', 'fas fa-list', CommercePackage::class);
        yield MenuItem::linkToCrud('Invoices', 'fas fa-list', CommerceInvoice::class);
        yield MenuItem::linkToCrud('Discount Codes', 'fas fa-list', CommerceDiscountCode::class);
        yield MenuItem::linkToCrud('Payment Gateways', 'fas fa-list', CommerceGatewayInstance::class);

        yield MenuItem::linkToCrud('Purchases', 'fas fa-list', CommercePurchase::class);
        yield MenuItem::linkToCrud('User Subscriptions', 'fas fa-list', CommerceUserSubscription::class);
        yield MenuItem::linkToCrud('Transactions', 'fas fa-list', CommerceTransaction::class);
        if ($_ENV['APP_ENV'] == "dev")
        {
            yield MenuItem::linkToCrud('DEV: Gateway Types', 'fas fa-list', CommerceGatewayType::class);
        }
    }
}
