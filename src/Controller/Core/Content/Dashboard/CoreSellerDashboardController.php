<?php

namespace App\Controller\Core\Content\Dashboard;

use App\Entity\Commerce\CommerceDiscountCode;
use App\Entity\Commerce\CommerceGatewayInstance;
use App\Entity\Commerce\CommerceGatewayType;
use App\Entity\Commerce\CommerceInvoice;
use App\Entity\Commerce\CommercePackage;
use App\Entity\Commerce\CommercePackageGroup;
use App\Entity\Commerce\CommercePurchase;
use App\Entity\Commerce\CommerceTransaction;
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
 * @IsGranted("ROLE_SELLER")
 */
class CoreSellerDashboardController extends AbstractDashboardController
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
     * @Route("/reseller", name="app_dashboard_seller")
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
    }

}
