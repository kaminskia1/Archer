<?php

namespace App\Controller;

use App\Entity\CommerceUserSubscription;
use App\Entity\RegistrationCode;
use App\Repository\RegistrationCodeRepository;
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
