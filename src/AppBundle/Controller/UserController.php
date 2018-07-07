<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user")

 */
class UserController extends Controller
{
    /**
     * @Route("/home", name="member_area")
     */
    public function ConnectedAction()
    {
        return $this->render('user/index.html.twig');
    }


}
