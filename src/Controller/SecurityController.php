<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Security\UserRegistrationDTO;
use App\Form\Security\UserRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/registration", name="app_registration", methods={"GET", "POST"})
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $user = new User();
        $formDTO = new UserRegistrationDTO();
        $form = $this->createForm(UserRegistrationType::class, $formDTO);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setEmail($formDTO->email)
                ->setPassword(
                    $passwordEncoder->encodePassword($user, $formDTO->plainPassword)
                )
            ;
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render(
            'pages/registration.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index');
        }

        return $this->render(
            'pages/login.twig',
            [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
            ]
        );
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
