<?php

namespace App\Controller\Core\Content\Dashboard;

use App\Controller\Commerce\Dashboard\Reseller\CommerceInvoiceCrudController;
use App\Controller\Commerce\Dashboard\Reseller\CommerceLicenseKeyCrudController;
use App\Entity\Commerce\CommerceLicenseKey;
use App\Entity\Commerce\CommerceInvoice;
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
        return $this->render('dashboard/dashboard_seller_landing.html.twig');
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
        yield MenuItem::linkToUrl('Store', 'fa fa-store', $this->generateUrl("app_commerce_store"));

        yield MenuItem::section('Reseller');
        yield MenuItem::linkToCrud('My Keys', 'fas fa-list', CommerceLicenseKey::class)->setController(CommerceLicenseKeyCrudController::class);
        yield MenuItem::linkToCrud('My Invoices', 'fas fa-list', CommerceInvoice::class)->setController(CommerceInvoiceCrudController::class);;
    }

}
