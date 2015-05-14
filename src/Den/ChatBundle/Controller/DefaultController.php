<?php

namespace Den\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Den\ChatBundle\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Chat controller
 * 
 * @author Denis Dolgopolov <dolgopolovdc@gmail.com>
 *
 */
class DefaultController extends Controller
{
    /**
     * Chat page
     */
    public function indexAction()
    {
        $em = $this->get('doctrine')->getEntityManager();
        $messages = $em->getRepository('DenChatBundle:Message')->findAll();
        
        $message = new Message();
        $form = $this->get('form.factory')
        ->createBuilder('form', $message)
        ->add('message', 'text')
        ->getForm();
        
        return $this->render('DenChatBundle:Default:index.html.twig', array('messages' => $messages, 'form' => $form->createView()));
    }
    
    /**
     * New message
     */
    public function newAction()
    {
    	$message = new Message();
    	$user = $this->get('security.context')->getToken()->getUser();
    	
        $message->setUser($user);
        $message->setCreated(new \DateTime());
        $message->setUpdated($message->getCreated());
        
        
    	$form = $this->get('form.factory')
        	->createBuilder('form', $message)
        	->add('message', 'text')
    	    ->getForm();
    	$request = $this->get('request');
    	if ($request->getMethod() == 'POST') {
    		$form->bind($request);
    		if ($form->isValid()) {
    			$em = $this->get('doctrine')->getEntityManager();
    			$em->persist($message);
    			$em->flush();
    			return $this->redirect($this->generateUrl('den_chat_homepage'));
    		}
    	}
    	return $this->render('DenChatBundle:Default:index.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * Form message
     * 
     * @param Request $request
     */
    public function formAction(Request $request)
    {
        $id = $request->request->get('id');
        
        $em = $this->get('doctrine')->getEntityManager();
        $message = $em->getRepository('DenChatBundle:Message')->find($id);
        
        $form = $this->get('form.factory')
            ->createBuilder('form', $message)
            ->add('message', 'textarea')
            ->add('id', 'hidden')
            ->getForm();
        
        return $this->render('DenChatBundle:Default:form.html.twig', array('form' => $form->createView(), 'id' => $id));
    }
    
    /**
     * Ajax edit message
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAjaxAction(Request $request)
    {
        $message = new Message();
        $requestForm = $request->request->get('form');
        
        $em = $this->get('doctrine')->getEntityManager();
        $message = $em->getRepository('DenChatBundle:Message')->find($requestForm['id']);
        
        if (!$message) {
            throw $this->createNotFoundException('Unable to find Message entity.');
        }
        
        $form = $this->get('form.factory')
            ->createBuilder('form', $message)
            ->add('message')
            ->add('id')
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em->persist($message);
            $em->flush();
        }
        
        return new JsonResponse($message->getMessage());
    }
}
