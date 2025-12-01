<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private readonly EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // Rediriger les utilisateurs déjà connectés
        if ($this->getUser()) {
            // Rediriger vers l'espace approprié selon le rôle
            $user = $this->getUser();
            $roles = $user->getRoles();

            if (in_array('ROLE_ADMIN', $roles)) {
                return $this->redirectToRoute('app_dashboard');
            } elseif (in_array('ROLE_ARTISTE', $roles)) {
                return $this->redirectToRoute('app_artist_space');
            } else {
                return $this->redirectToRoute('app_client_space');
            }
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var string $plainPassword */
                $plainPassword = $form->get('plainPassword')->getData();
                $role = $form->get('role')->getData();

                // Set role - s'assurer que c'est un tableau
                $user->setRoles([$role]);

                // Set artist information if applicable
                if ($role === 'ROLE_ARTISTE') {
                    $biographie = $form->get('biographie')->getData();
                    $styleArtistique = $form->get('styleArtistique')->getData();
                    $user->setBiographie($biographie);
                    $user->setStyleArtistique($styleArtistique);
                }

                // Encode password
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

                $entityManager->persist($user);
                $entityManager->flush();

                // Send confirmation email
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('no-reply@artmove.com', 'ArtMove Bot'))
                        ->to($user->getEmail())
                        ->subject('Veuillez confirmer votre email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );

                $this->addFlash('success', 'Inscription réussie! Veuillez vérifier votre email.');

                return $this->redirectToRoute('app_login');

            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'inscription: ' . $e->getMessage());
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
{
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    try {
        /** @var User $user */
        $user = $this->getUser();
        $this->emailVerifier->handleEmailConfirmation($request, $user);
    } catch (VerifyEmailExceptionInterface $exception) {
        $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
        return $this->redirectToRoute('app_register');
    }

    $this->addFlash('success', 'Votre adresse email a été vérifiée avec succès.');

    // Rediriger vers l'espace approprié selon le rôle
    $roles = $user->getRoles();
    if (in_array('ROLE_ADMIN', $roles)) {
        return $this->redirectToRoute('app_dashboard');
    } elseif (in_array('ROLE_ARTISTE', $roles)) {
        return $this->redirectToRoute('app_artist_space');
    } else {
        return $this->redirectToRoute('app_client_space');
    }
}
}
