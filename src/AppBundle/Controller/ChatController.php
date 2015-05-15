<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Message;

/**
 * Class Chat controller
 * 
 * @Route("/")
 * @author Denis Dolgopolov <dolgopolovdc@gmail.com>
 *
 */
class ChatController extends Controller
{
    /**
     * Chat page
     * 
     * @Route("/", name="chat_homepage")
     */
    public function indexAction()
    {
        $em = $this->get('doctrine')->getEntityManager();
        $messages = $em->getRepository('AppBundle:Message')->findAll();
        
        $message = new Message();
        $form = $this->get('form.factory')
        ->createBuilder('form', $message)
        ->add('message', 'text')
        ->getForm();
        
        return $this->render('AppBundle:Chat:index.html.twig', array(
            'messages' => $messages,
            'form' => $form->createView()
        ));
    }
    
    /**
     * New message
     * 
     * @Route("/message_new", name="message_new")
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
    			return $this->redirect($this->generateUrl('chat_homepage'));
    		}
    	}
    	return $this->render('AppBundle:Chat::index.html.twig', array(
    	        'form' => $form->createView()
    	));
    }
    
    /**
     * Form message
     * 
     * @Route("/message_form", name="message_form")
     * @param Request $request
     */
    public function formAction(Request $request)
    {
        $id = $request->request->get('id');
        
        $em = $this->get('doctrine')->getEntityManager();
        $message = $em->getRepository('AppBundle:Message')->find($id);
        
        $form = $this->get('form.factory')
            ->createBuilder('form', $message)
            ->add('message', 'textarea')
            ->add('id', 'hidden')
            ->getForm();
        
        return $this->render('AppBundle:Chat:form.html.twig', array(
            'form' => $form->createView(),
            'id' => $id
        ));
    }
    
    /**
     * Ajax edit message
     * 
     * @Route("/message_edit", name="message_edit_ajax")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAjaxAction(Request $request)
    {
        $message = new Message();
        $requestForm = $request->request->get('form');
        
        $em = $this->get('doctrine')->getEntityManager();
        $message = $em->getRepository('AppBundle:Message')->find($requestForm['id']);
        
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
        
        return new JsonResponse(
            $message->getMessage()
        );
    }
}
