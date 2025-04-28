<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Form\UtilisateurType;
use App\Form\EditFormType;
use App\Repository\UtilisateurRepository;
use App\Repository\RoleRepository;




class SecurityController extends AbstractController
{
    private $urlGenerator;
    private $emailVerifier;
    private $utilisateurRepository;

    public function __construct(UrlGeneratorInterface $urlGenerator,  EmailVerifier $emailVerifier,UtilisateurRepository $utilisateurRepository)
    {
        $this->urlGenerator = $urlGenerator;
        $this->emailVerifier = $emailVerifier;
        $this->utilisateurRepository = $utilisateurRepository;

    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

       
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'sitekey' => $_ENV['RECAPTCHA_SITE_KEY'],
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    public function onAuthenticationSuccess($request, $token, $providerKey)
    {
        $user = $token->getUser();
        
        // Check if user is banned
        if ($user->getStatus() === 0) {
            $this->addFlash('error', 'Cet utilisateur est banni. Veuillez contacter l\'administration.');
            
            return new RedirectResponse($this->urlGenerator->generate('app_logout'));
        }

        $role = $user->getRole();
        if ($role && $role->getId_role() === 1) {
            return new RedirectResponse($this->urlGenerator->generate('app_utilisateur_index'));
        } else {
            return new RedirectResponse($this->urlGenerator->generate('app_hebergement_front_index'));
        }
    }
    
    #[Route('/banned', name: 'app_banned')]
public function banned(): Response
{
    // Show a page with a message that the user is banned
    $this->addFlash('error', 'Cet utilisateur est banni. Veuillez contacter l\'administration.');

    return $this->render('security/banned.html.twig'); // Create a simple view for banned users
}

    /*
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        RoleRepository $roleRepository

    ): Response {
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getMot_de_passe()
            );
            $user->setMot_de_passe($hashedPassword);

          
            $user->setStatus(1);
            $user->setVerif(0);
            $user->setVerification_token('');
            $user->setReset_password_token('');

           $role = $roleRepository->find(2); 
           $user->setRole($role);
    
            $user->setImage_url('');

            $entityManager->persist($user);
            $entityManager->flush();

 $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('med.azyz.mejry@gmail.com', 'MedTravel'))
                    ->to((string) $user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('app_utilisateur_index'); 
        }

        return $this->render('registration/register.html.twig', [
            'registerForm' => $form->createView(),
        ]);
    }
#[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            /** @var Utilisateur $user */
         /*   $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }*/
}
