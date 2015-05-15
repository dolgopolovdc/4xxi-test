<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Message;
use AppBundle\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->get('doctrine')->getEntityManager();
        
        $message = new Message();
        $form = $this->createForm(new MessageType(), $message);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setUser($user);
            $message->setCreated(new \DateTime());
            $message->setUpdated($message->getCreated());
            
            $em->persist($message);
            $em->flush();
            
            return $this->redirect($this->generateUrl('chat_homepage'));
        }

        $messages = $em->getRepository('AppBundle:Message')->findAll();
        
        return $this->render('AppBundle:Chat:index.html.twig', array(
            'messages' => $messages,
            'form' => $form->createView()
        ));
    }
    
    /**
     * Form message
     * 
     * @Route("/message_form/{id}", name="message_form")
     * @Security("user == message.getUser()")
     * @param Request $request
     */
    public function formAction(Message $message)
    {
        $form = $this->createForm(new MessageType(), $message);
        
        return $this->render('AppBundle:Chat:form.html.twig', array(
            'form' => $form->createView(),
            'message' => $message
        ));
    }
    
    /**
     * Ajax edit message
     * 
     * @Route("/message_edit/{id}", name="message_edit_ajax")
     * @Security("user == message.getUser()")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function editAjaxAction(Request $request, Message $message)
    {
        $form = $this->createForm(new MessageType(), $message);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine')->getEntityManager();
            $em->persist($message);
            $em->flush();
        }
        
        return new JsonResponse(
            $message->getMessage()
        );
    }
}
