<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserController extends AbstractController
{
    public function getAllUsers(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        return $this->json($users);
    }

    /**
     * @Route("/users", methods={"POST"})
     */
    public function createUser(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validar que los campos requeridos estén presentes
        if (empty($data['name']) || empty($data['email']) || empty($data['age']) || empty($data['password'])) {
            return $this->json(['error' => 'All fields are required.'], 400);
        }

        // Crear el usuario
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setAge($data['age']);
        $user->setPassword(password_hash($data['password'], PASSWORD_ARGON2I)); // Mejor opción de seguridad

        // Persistir el usuario
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user, 201);
    }

    /**
     * @Route("/users/{id}", name="update_user", methods={"PUT"})
     */
    public function updateUser(int $id, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Buscar el usuario
        $user = $userRepository->find($id);
        if (!$user) {
            return $this->json(['error' => 'User not found.'], 404);
        }

        // Obtener los datos de la solicitud
        $data = json_decode($request->getContent(), true);
        $user->setName($data['name'] ?? $user->getName());
        $user->setEmail($data['email'] ?? $user->getEmail());
        $user->setAge($data['age'] ?? $user->getAge());

        // Si se proporciona una nueva contraseña, actualizarla
        if (isset($data['password'])) {
            $user->setPassword(password_hash($data['password'], PASSWORD_ARGON2I)); // Mejor opción de seguridad
        }

        // Persistir los cambios
        $entityManager->flush();

        return $this->json($user);
    }

    /**
     * @Route("/users/{id}", name="user_delete", methods={"DELETE"})
     */
    public function deleteUser(int $id, UserRepository $userRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Buscar el usuario
        $user = $userRepository->find($id);
        if (!$user) {
            return $this->json(['error' => 'User not found.'], 404);
        }

        // Eliminar el usuario
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse(null, 204);
    }
}
