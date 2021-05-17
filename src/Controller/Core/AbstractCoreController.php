<?php

namespace App\Controller\Core;

use App\Model\CoreTraitModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class AbstractCoreController
 * @package App\Controller\Core
 */
class AbstractCoreController extends AbstractController
{
    use CoreTraitModel;

}
