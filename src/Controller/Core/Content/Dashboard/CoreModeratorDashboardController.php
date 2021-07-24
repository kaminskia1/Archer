<?php

namespace App\Controller\Core\Content\Dashboard;


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
 * @IsGranted("ROLE_MODERATOR")
 */
class CoreModeratorDashboardController extends AbstractDashboardController
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
     * @Route("/moderation", name="app_dashboard_moderator")
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
