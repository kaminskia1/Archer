<?php

namespace App\Controller\Commerce\Site;

use App\Controller\Commerce\AbstractCommerceController;
use App\Entity\Commerce\CommerceLicenseKey;
use App\Entity\Commerce\CommerceUserSubscription;
use App\Entity\Core\CoreGroup;
use App\Entity\Core\CoreRegistrationCode;
use App\Entity\Core\CoreUser;
use App\Model\CommerceTraitModel;
use App\Module\Core\CorePasswordHasher;
use App\Security\LoginAuthenticator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class LicenseController
 *
 * @package App\Controller
 */
class LicenseController extends AbstractCommerceController
{

    use CommerceTraitModel;


    /**
     * @Route("/redeem", name="app_commerce_redeem")
     * @param Security                     $security
     * @param Request                      $request
     * @param EntityManagerInterface       $em
     * @param GuardAuthenticatorHandler    $guardHandler
     * @param LoginAuthenticator           $authenticator
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return Response
     * @throws Exception
     */
    public function redeem(Security $security, Request $request, EntityManagerInterface $em, GuardAuthenticatorHandler $guardHandler, LoginAuthenticator $authenticator, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $key = new CommerceLicenseKey(null, null, null, null);

        $form = $this->createFormBuilder($key)
            ->add('code', TextType::class)
            ->getForm();


        // User is logged in
        if ($security->isGranted("ROLE_USER")) {

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                /**
                 * @var CommerceLicenseKey $key
                 */
                $key = $this
                    ->getDoctrine()
                    ->getRepository(CommerceLicenseKey::class)
                    ->findOneBy(['code' => $form->getData()->getCode()]);

                // Ensure code exists
                if ($key == null) {
                    return $this->render("module/commerce/license/redeem.html.twig", [
                        'name' => 'redeem',
                        'form' => $form->createView(),
                        'error' => 'Invalid Code!',
                        'auth' => true,
                    ]);
                }

                // Grab preexisting subscription
                $sub = $this
                    ->getDoctrine()
                    ->getRepository(CommerceUserSubscription::class)
                    ->findOneBy(['commercePackageAssoc' => $key->getPackage(), 'user' => $this->getUser()]);

                // Check if code already used
                if (!$key->getActive() || $key->getUsedBy() != null || !$key->getPackage()->getIsKeyEnabled()) {

                    // Check if logged in user redeemed this.
                    // If so, show redeemed page
                    // If not, show invalid code
                    if ($key->getUsedBy() == $this->getUser()) {
                        return $this->render("module/commerce/license/redeem_success.html.twig", [
                            'name' => 'redeem',
                            'sub' => $sub,
                            'key' => $key,
                            'password' => null,
                            'newuser' => false,
                            'user' => $this->getUser(),
                            'first' => false,
                        ]);
                    } else {
                        return $this->render("module/commerce/license/redeem.html.twig", [
                            'name' => 'redeem',
                            'form' => $form->createView(),
                            'error' => 'Invalid Code!',
                            'auth' => true,
                        ]);
                    }
                }


                // If nonexistant, create and set basic data
                if ($sub == null) {
                    $sub = new CommerceUserSubscription();
                    $sub->setUser($this->getUser());
                    $sub->setCommercePackageAssoc($key->getPackage());
                }

                // Add time
                $sub->addTime($key->getDuration());

                // Set key as used
                $key->setActive(false);
                $key->setUsedBy($this->getUser());

                // Save sub and flush updated entities
                $em->persist($sub);
                $em->flush();

                return $this->render("module/commerce/license/redeem_success.html.twig", [
                    'name' => 'redeem',
                    'sub' => $sub,
                    'key' => $key,
                    'password' => null,
                    'newuser' => false,
                    'user' => $this->getUser(),
                    'first' => true,
                ]);

            }

            return $this->render("module/commerce/license/redeem.html.twig", [
                'name' => 'redeem',
                'form' => $form->createView(),
                'error' => null,
                'auth' => true,
            ]);

        }

        // User is not logged in, must create a fresh one
        {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                /**
                 * @var CommerceLicenseKey $code
                 */
                $key = $this
                    ->getDoctrine()
                    ->getRepository(CommerceLicenseKey::class)
                    ->findOneBy(['code' => $form->getData()->getCode()]);

                // Validate Code. No need to separate as no user is logged in.
                if ($key == null || !$key->getActive() || $key->getUsedBy() != null ||
                    !$key->getPackage()->getIsKeyEnabled()) {
                    return $this->render("module/commerce/license/redeem.html.twig", [
                        'name' => 'redeem',
                        'form' => $form->createView(),
                        'error' => 'Invalid Code!',
                        'auth' => false,
                    ]);
                }


                // Registration code creation
                {
                    $reg = new CoreRegistrationCode();
                    $reg->populateCode();
                    $reg->setEnabled(false);
                    $reg->setStaffMessage("Generated by License#" . $key->getCode());
                    $reg->setUsageDate(new DateTime());
                    $em->persist($reg);
                    $em->flush();
                }


                // User creation
                {
                    $user = new CoreUser();
                    $password = mb_substr(md5(random_bytes(10)), 0, 10);

                    $user->setPassword($password);
                    $user->setRegistrationCode($reg);
                    $user->setStaffNote("Generated by License#" . $key->getCode());
                    $user->addGroup($this
                        ->getDoctrine()
                        ->getRepository(CoreGroup::class)
                        ->findOneBy(['internalName' => 'ROLE_USER'])
                    );
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            CorePasswordHasher::hashPassword($password)
                        )
                    );
                    $user->__populate();

                    $reg->setUsedBy($user);
                    $em->persist($user);
                    $em->flush();
                }

                // Subscription Creation
                {
                    $sub = new CommerceUserSubscription();
                    $sub->setUser($user);
                    $sub->setCommercePackageAssoc($key->getPackage());
                    $sub->addTime($key->getDuration());
                    $em->persist($sub);
                    $em->flush();
                }

                // Key completion
                {
                    $key->setActive(false);
                    $key->setUsedBy($user);
                }

                /**
                 * @TODO very jank, figure out how pass this through properly (create a separate firewall?)!
                 * Hides a redirect in the request object, which will eventually be passed to the default authenticator,
                 * in where it will be utilized!
                 */
                $request->__specialRedirect = $this->redirectToRoute('app_commerce_redeem_success_newuser', [
                    'subid' => $sub->getId(),
                    'keyid' => $key->getId(),
                    'password' => $password,
                ]);

                // Final flush as all modifications are complete
                $em->flush();

                // Return authentication and redirect to key page
                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );

            }

            return $this->render("module/commerce/license/redeem.html.twig", [
                'name' => 'redeem',
                'form' => $form->createView(),
                'error' => null,
                'auth' => false,
            ]);
        }

    }

    /**
     * @Route("/redeem/success/{subid}/{keyid}/{password}", name="app_commerce_redeem_success_newuser")
     * @isGranted("ROLE_USER")
     */
    public function redeemSuccessNewUser(int $subid, int $keyid, string $password)
    {
        // Grab subscription and key from database
        $sub = $this->getDoctrine()->getRepository(CommerceUserSubscription::class)->find($subid);
        $key = $this->getDoctrine()->getRepository(CommerceLicenseKey::class)->find($keyid);

        dump($key);
        dump($sub);
        // Validate that both provided key and subscription belong to user
        if ($sub != null && $key != null && $this->getUser() != null && $sub->getUser()->getId() == $this->getUser()->getId() && $key->getUsedBy()->getId() == $this->getUser()->getId())
        {
            return $this->render('module/commerce/license/redeem_success.html.twig', [
                'name' => 'redeem',
                'sub' => $sub,
                'key' => $key,
                'password' => $password,
                'newuser' => true,
                'user' => $this->getUser(),
                'first' => true,
            ]);
        }

        return $this->render('module/core/error/400.html.twig');

    }

}