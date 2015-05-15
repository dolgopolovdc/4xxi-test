<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class User Controller
 * 
 * @author Denis Dolgopolov <dolgopolovdc@gmail.com>
 *
 */
class UserController extends Controller
{
    /**
     * User profile page
     * 
     * @Route("/profile", name="user_profile")
     * @param Request $request
     */
    public function profileAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $name = $user->getUsername();
        
        $em = $this->get('doctrine')->getEntityManager();
        $user = $em->getRepository('AppBundle:User')->find($user->getId());
        
        $form = $this->get('form.factory')
        ->createBuilder('form', $user)
        ->add('username')
        ->add('id', 'hidden')
        ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em->persist($user);
            $em->flush();
        }
        
        return $this->render('AppBundle:User:profile.html.twig', array(
            'name' => $name,
            'form' => $form->createView()
        ));
    }
}
