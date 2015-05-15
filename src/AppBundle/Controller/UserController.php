<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class User Controller
 * 
 * @author Denis Dolgopolov <dolgopolovdc@gmail.com>
 *
 */
class UserController extends Controller
{
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
