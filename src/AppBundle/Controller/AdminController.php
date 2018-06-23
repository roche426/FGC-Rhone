<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AdminController
 * @package AppBundle\Controller
 * @Route("/admin", name="admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/index", name="adminHome")
     */
    public function ConnectedAction()
    {
        return $this->render('admin/index.html.twig');
    }


}
