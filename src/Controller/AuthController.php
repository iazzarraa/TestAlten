<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
    private $passwordHasher;
    private $em;
    private $jwtManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, JWTTokenManagerInterface $jwtManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->em = $em;
        $this->jwtManager = $jwtManager;
    }

    // Route pour l'enregistrement d'un utilisateur
    #[Route('/account', name: 'create_account', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifie que tous les champs sont présents
        if (!isset($data['email'], $data['password'], $data['username'], $data['firstname'])) {
            return new JsonResponse(['error' => 'Missing parameters'], Response::HTTP_BAD_REQUEST);
        }

        // Crée l'utilisateur
        $user = new User();
        $user->setEmail($data['email'])
            ->setUsername($data['username'])
            ->setFirstname($data['firstname'])
            ->setPassword($this->passwordHasher->hashPassword($user, $data['password'])); // hashPassword est utilisé ici

        $this->em->persist($user);
        $this->em->flush();

        return new JsonResponse(['message' => 'User created'], Response::HTTP_CREATED);
    }

    // Route pour obtenir un token JWT
    #[Route('/token', name: 'get_token', methods: ['POST'])]
    public function getToken(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['password'])) {
            return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
        }

        // Vérifie l'email et le mot de passe
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        // Vérifie si l'utilisateur existe et si le mot de passe est valide
        if (!$user || !$this->passwordHasher->isPasswordValid($user, $data['password'])) {  // isPasswordValid est utilisé ici
            return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        // Crée le token JWT
        $payload = [
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'iat' => time(),  
            'exp' => time() + 36000,  // expiration dans 1 heure
        ];
        $token = $this->jwtManager->createFromPayload($user, $payload);

        return new JsonResponse(['token' => $token]);
    }
}
