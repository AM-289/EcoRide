<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController {
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response {
        $data = new ContactDTO();
        $form = $this->createForm(ContactType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $mail = (new TemplatedEmail())
                    ->to($data->service)
                    ->from($data->email)
                    ->subject('Demande de contact')
                    ->htmlTemplate('contact/email.html.twig')
                    ->context(['data' => $data]);
                
                $mailer->send($mail);
                $this->addFlash('success', 'Votre e-mail a bien été envoyé');
                return $this->redirectToRoute('contact');
            } catch (TransportExceptionInterface $e) {
                $this->addFlash('error', 'impossible d\'envoyer l\'email');
            }
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form,
        ]);
    }
}