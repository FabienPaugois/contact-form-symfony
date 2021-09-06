<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;

use App\Entity\Mail;
use App\Form\MailType;
use App\Service\MailManager;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailManager $mailManager): Response
    {
        $mail = new Mail();
        $form = $this->createForm(MailType::class, $mail);

        $form->handleRequest($request);
        $sent = false;
        if ($form->isSubmitted() && $form->isValid()) {

            $sent = $mailManager->sendMail($mail);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mail);
            $entityManager->flush();
            
        }
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView(),
            'sent' => $sent,
        ]);
    }
}
