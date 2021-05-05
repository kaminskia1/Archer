<?php

namespace App\Controller\Core;

use App\Entity\Commerce\CommerceUserSubscription;
use App\Entity\Core\RegistrationCode;
use App\Repository\Core\RegistrationCodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        return new Response();

    }
}
