<?php

namespace App\Controller\Core\Site;

use App\Entity\Core\CoreGroup;
use App\Entity\Core\CoreUser;
use App\Model\CoreTraitModel;
use App\Controller\Core\AbstractCoreController;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TestController extends AbstractCoreController
{

    use CoreTraitModel;

    /**l
     * @Route("/test", name="test")
     * @isGranted("ROLE_ADMIN")
     */
    public function index(EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $user->addGroup($this->getDoctrine()->getRepository(CoreGroup::class)->findBy(['internalName'=>'ROLE_ADMIN']));
        $em->flush();
        return $this->render('base.html.twig');

    }
}
