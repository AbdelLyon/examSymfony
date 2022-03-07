<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{

  #[Route('/register', name: 'user_register')]
  public function register(
    Request $request,
    UserPasswordHasherInterface $userPasswordHasher,
    EntityManagerInterface $em
  ): Response {

    $user = new User;
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user->setPassword(
        $userPasswordHasher->hashPassword(
          $user,
          $form->get('plainPassword')->getData()
        )
      );

      $em->persist($user);
      $em->flush();
      return $this->redirectToRoute('user_login');
    }

    return $this->render('user/register.html.twig', [
      'registrationForm' => $form->createView(),
    ]);
  }

  #[Route('/login', name: 'user_login')]
  public function index(AuthenticationUtils $authenticationUtils): Response
  {
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('user/login.html.twig', [
      'last_username' => $lastUsername,
      'error' => $error,
    ]);
  }


  #[Route('/logout', name: 'user_logout')]
  public function logout()
  {
    // controller can be blank: it will never be called!
    throw new \Exception('Don\'t forget to activate logout in security.yaml');
  }
}
