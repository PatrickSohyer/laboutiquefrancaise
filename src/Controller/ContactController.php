<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/nous-contacter", name="contact")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('notice', "Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs délais.");

            $content = "Bonjour,</br>Vous avez reçus un message de <strong>" . $form->getData()['prenom'] . " " . $form->getData()['nom'] . "</strong></br>Adresse email : <strong>" . $form->getData()['email'] . "</strong> </br>Message : " . $form->getData()['content'] . "</br>";
            $mail = new Mail();
            $mail->send('patrick.sohyer@adenformations.com', 'La Boutique Française', 'Vous avez reçus une nouvelle demande de contact', $content);
        }


        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
