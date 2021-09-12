<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Service;
use App\Entity\Mail;
use App\Form\MailType;
use App\Repository\ServiceRepository;
use App\Service\MailManager;

class ContactController extends AbstractController
{
    /**
     * @Route("/services", name="get_services", methods={"GET"})
     */
    public function get_services(ServiceRepository $serviceRepo): Response
    {

        $services = $serviceRepo->findAll();
        $servicesArray = [];
        foreach ($services as $service) {
            $servicesArray[] = [
                "id" => $service->getId(),
                "name" => $service->getName(),
                "email" => $service->getEmail()
            ];
        }

        return $this->json(["services" => $servicesArray]);
    }

    /**
     * @Route("/contact", name="contact", methods={"POST"})
     */
    public function post_mail(Request $request, MailManager $mailManager): Response
    {

        $mailParamArray = $request->toArray();

        $mail = new Mail();

        $form = $this->createForm(MailType::class, $mail);
        $form->submit($mailParamArray);

        if ($form->isSubmitted() && $form->isValid()) {

            $mailManager->sendMail($mail);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mail);
            $entityManager->flush();

            return $this->json(["data" => $request->toArray()]);
        } else if ($form->isValid() == false) {

            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }

            return $this->json(["status" => "failed", "simpleErrors" => $errors, "extendedErrors" => $form->getErrors(true)]);
        } else {

            return $this->json(["status" => "failed", "error" => "Un problème est survenu !"]);
        }
    }
}
