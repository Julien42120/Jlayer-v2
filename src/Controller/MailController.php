<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    /**
     * @Route("/mail", name="mail")
     */
    public function index(): Response
    {
        return $this->render('mail/index.html.twig', [
            'controller_name' => 'MailController',
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/sendmail", name="send_mail")
     */
    public function sendEmail(MailerInterface $mailer, Request $request)
    {
        $pseudo = $request->get('pseudo');
        $mail = $request->get('email');
        $message = $request->get('message');
        if ($pseudo && $mail &&  $message) {

            $email = (new TemplatedEmail())
                ->from(new Address($mail, 'User Mail'))
                ->to('julienmartin42120@gmail.com')
                ->subject('Question Jlayers')
                ->htmlTemplate('emails/templateEmail.html.twig')
                ->context([
                    'user' => $pseudo,
                    'message' => $message,
                ]);

            $mailer->send($email);
        } else {

            $this->addFlash('error', 'Le formulaire n\'est rempli correctement');

            return $this->redirectToRoute('mail', [], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}
