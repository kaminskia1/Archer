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
use App\Entity\Core\CoreUser;
use App\Entity\Core\CoreRegistrationCode;

use Doctrine\ORM\EntityManagerInterface;
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
 * Class CoreAdminDashboardController
 * @package App\Controller\Admin
 * @IsGranted("ROLE_ADMIN")
 */
class CoreAdminDashboardController extends AbstractDashboardController
{

    /**
     * @var EntityManagerInterface Entity Manager instance
     */
    private $entityManager;

    /**
     * CoreAdminDashboardController constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        // Inject EntityManager
        $this->entityManager = $entityManager;
    }

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

        /**
         * Begin Core section
         */
        yield MenuItem::section('Core');
        yield MenuItem::linkToCrud('Users', 'fas fa-list', CoreUser::class);
        yield MenuItem::linkToCrud('Registration Codes', 'fas fa-list', CoreRegistrationCode::class);
        yield MenuItem::linkToCrud('Site Modules', 'fas fa-list', CoreModule::class);


        /**
         * Begin Commerce section
         * Validate that commerce is installed and enabled
         */
        if ($this->entityManager->getRepository(CoreModule::class)->isModuleLoaded('Commerce'))
        {
            yield MenuItem::section('Commerce');
            yield MenuItem::linkToCrud('Package Groups', 'fas fa-list', CommercePackageGroup::class);
            yield MenuItem::linkToCrud('Packages', 'fas fa-list', CommercePackage::class);
            yield MenuItem::linkToCrud('Invoices', 'fas fa-list', CommerceInvoice::class);
            yield MenuItem::linkToCrud('Discount Codes', 'fas fa-list', CommerceDiscountCode::class);
            yield MenuItem::linkToCrud('Payment Gateways', 'fas fa-list', CommerceGatewayInstance::class);
            yield MenuItem::linkToCrud('Purchases', 'fas fa-list', CommercePurchase::class);
            yield MenuItem::linkToCrud('CoreUser Subscriptions', 'fas fa-list', CommerceUserSubscription::class);
            yield MenuItem::linkToCrud('Transactions', 'fas fa-list', CommerceTransaction::class);
            if ($_ENV['APP_ENV'] == "dev")
            {
                yield MenuItem::linkToCrud('DEV: Gateway Types', 'fas fa-list', CommerceGatewayType::class);
            }
        }

    }
}
