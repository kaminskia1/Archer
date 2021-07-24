<?php

namespace App\Controller\Core\Content\Dashboard;

use App\Entity\Commerce\CommerceDiscountCode;
use App\Entity\Commerce\CommerceGatewayInstance;
use App\Entity\Commerce\CommerceGatewayType;
use App\Entity\Commerce\CommerceInvoice;
use App\Entity\Commerce\CommercePackage;
use App\Entity\Commerce\CommercePackageGroup;
use App\Entity\Commerce\CommercePurchase;
use App\Entity\Commerce\CommerceUserSubscription;
use App\Entity\Core\CoreGroup;
use App\Entity\Core\CoreModule;
use App\Entity\Core\CoreRegistrationCode;
use App\Entity\Core\CoreUser;
use App\Entity\Logger\LoggerCommand;
use App\Entity\Logger\LoggerCommandAuth;
use App\Entity\Logger\LoggerCommandUserInfraction;
use App\Entity\Logger\LoggerCommandUserSubscription;
use App\Entity\Logger\LoggerSiteAuthLogin;
use App\Entity\Logger\LoggerSiteRequest;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 * Class CoreAdminDashboardController
 * @package App\Controller\Core\Content\Dashboard
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
        return $this->render('dashboard/dashboard_landing.html.twig');
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
        yield MenuItem::linkToCrud('Groups', 'fas fa-list', CoreGroup::class);
        yield MenuItem::linkToCrud('Registration Codes', 'fas fa-list', CoreRegistrationCode::class);
        yield MenuItem::linkToCrud('Site Modules', 'fas fa-list', CoreModule::class);


        /**
         * Begin Commerce section
         * Validate that commerce is installed and enabled
         */
        if ($this->entityManager->getRepository(CoreModule::class)->isModuleLoaded('Commerce')) {
            yield MenuItem::section('Commerce');
            yield MenuItem::linkToCrud('Package Groups', 'fas fa-list', CommercePackageGroup::class);
            yield MenuItem::linkToCrud('Packages', 'fas fa-list', CommercePackage::class);
            yield MenuItem::linkToCrud('Invoices', 'fas fa-list', CommerceInvoice::class);
            yield MenuItem::linkToCrud('Discount Codes', 'fas fa-list', CommerceDiscountCode::class);
            yield MenuItem::linkToCrud('Payment Gateways', 'fas fa-list', CommerceGatewayInstance::class);
            yield MenuItem::linkToCrud('Purchases', 'fas fa-list', CommercePurchase::class);
            yield MenuItem::linkToCrud('User Subscriptions', 'fas fa-list', CommerceUserSubscription::class);
            if ($_ENV['APP_ENV'] == "dev") {
                yield MenuItem::linkToCrud('DEV: Gateway Types', 'fas fa-list', CommerceGatewayType::class);
            }
        }

        /**
         * Begin Logger section
         * Validate that logger is installed and enabled
         */
        if ($this->entityManager->getRepository(CoreModule::class)->isModuleLoaded('Logger')) {
            yield MenuItem::section('Logger');
            yield MenuItem::linkToCrud('Commands', 'fas fa-list', LoggerCommand::class);
            yield MenuItem::linkToCrud('Loader Auth', 'fas fa-list', LoggerCommandAuth::class);
            yield MenuItem::linkToCrud('User Infractions', 'fas fa-list', LoggerCommandUserInfraction::class);
            yield MenuItem::linkToCrud('User Subscriptions', 'fas fa-list', LoggerCommandUserSubscription::class);
            yield MenuItem::linkToCrud('Site Auth', 'fas fa-list', LoggerSiteAuthLogin::class);
        }

    }
}
