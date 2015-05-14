<?php

namespace Den\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\User\User;

/**
 * Class Menu 
 * @author Denis Dolgopolov <dolgopolovdc@gmail.com>
 *
 */
class MenuController extends Controller
{
    public function userAction()
    {
        return $this->render('DenChatBundle:Menu:user.html.twig', array());
    }
    
}
