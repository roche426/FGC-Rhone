<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class UserController
 * @package AppBundle\Controller
 * @Route("/user", name="user")

 */
class UserController extends Controller
{
    /**
     * @Route("/connected", name="connected")
     */
    public function ConnectedAction()
    {
        return $this->render('user/connected.html.twig');
    }


}
