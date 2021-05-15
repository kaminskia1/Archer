<?php

namespace App\Controller\Core;

use App\Entity\Core\RegistrationCode;
use App\Entity\Core\User;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Uid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class RegistrationController
 * @package App\Controller
 */
class RegistrationController extends AbstractController
{

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginAuthenticator $authenticator
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginAuthenticator $authenticator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard_client');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

            $entityManager = $this->getDoctrine()->getManager();
            if (
                !is_null( $code = $entityManager
                    ->getRepository(RegistrationCode::class)
                    ->findByCodeEnabled(
                        $form
                            ->get('registrationCode')
                            ->getData()
                    )
                )
            )
            {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $user->setRegistrationCode($code);
                $user->__populate();

                $code->setUsedBy($user);
                $code->setUsageDate(new DateTime());
                $code->setEnabled(false);

                $entityManager->persist($user);
                $entityManager->flush();

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            }
        }

        return $this->render('core/registration/register.html.twig', [
            'form' => $form->createView(),
            'page_title' => "Archer",
            'target_path' => $this->generateUrl('app_dashboard_client'),
            'csrf_token_intention' => 'authenticate',
        ]);
    }

    /**
     * @Route("/reset-password", name="app_reset_password")
     * @param Request $request
     * @return Response
     */
    public function resetPassword(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard_client');
        }
        return new Response("Not yet implemented.");
    }
}
