<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
    public function ConnectedAction(Request $request)
    {
        return $this->render('user/connected.html.twig');
    }

}
