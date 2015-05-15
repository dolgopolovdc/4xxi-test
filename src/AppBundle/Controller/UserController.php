<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Form\UserType;

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
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     */
    public function profileAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        
        if($user instanceof \AppBundle\Entity\User) {
        
            $form = $this->createForm(new UserType(), $user);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }
            
            return $this->render('AppBundle:User:profile.html.twig', array(
                'form' => $form->createView()
            ));
        } else {
            return $this->render('AppBundle:User:profile.html.twig', array(
                'form' => false
            ));
        }
    }
}
