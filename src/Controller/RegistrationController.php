<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route ("/register", name="email_registration", methods={"POST"})
     */
    public function index(UserPasswordEncoderInterface $encoder, JWTTokenManagerInterface $JWTManager,
                          ValidatorInterface $validator, Request $request, UserRepository $userRepository): Response
    {
        $parameters = json_decode($request->getContent(), true);

        $email = $parameters['username'];
        $password = $parameters['password'];

        if ($userRepository->findOneBy(['email' => $email])) {
            return new JsonResponse([
                'error' => 'Email already in use'
            ]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setEmail($email);
        $encodedPassword = $encoder->encodePassword($user, $password);
        $user->setPassword($encodedPassword);
        $token = $JWTManager->create($user);
        $user->setToken($token);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            'token' => $token
        ]);
    }
}
