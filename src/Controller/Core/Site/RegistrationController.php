<?php

namespace App\Controller\Core\Site;

use App\Controller\Core\AbstractCoreController;
use App\Entity\Core\CoreRegistrationCode;
use App\Entity\Core\CoreUser;
use App\Form\Core\CoreRegistrationFormType;
use App\Model\CoreTraitModel;
use App\Module\Core\CorePasswordHasher;
use App\Security\LoginAuthenticator;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**/

/**
 * Class RegistrationController
 *
 * @package App\Controller
 */
class RegistrationController extends AbstractCoreController

{

    use CoreTraitModel;

    /**
     * @Route("/register", name="app_register")
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler    $guardHandler
     * @param LoginAuthenticator           $authenticator
     *
     * @return Response
     * @throws Exception
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginAuthenticator $authenticator): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard_client');
        }

        $user = new CoreUser();
        $form = $this->createForm(CoreRegistrationFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            if (
            !is_null($code = $entityManager
                ->getRepository(CoreRegistrationCode::class)
                ->findByCodeEnabled(
                    $form
                        ->get('registrationCode')
                        ->getData()
                )
            )
            ) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        CorePasswordHasher::hashPassword($form->get('plainPassword')->getData())
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

        return $this->render('module\core/registration/register.html.twig', [
            'form' => $form->createView(),
            'page_title' => "Archer",
            'target_path' => $this->generateUrl('app_dashboard_client'),
            'csrf_token_intention' => 'authenticate',
        ]);
    }

    /**
     * Reset Password Controller
     *
     * @Route("/reset-password", name="app_reset_password")
     *
     * @return Response
     */
    public function resetPassword(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard_client');
        }
        return new Response("Not yet implemented.");
    }

}
