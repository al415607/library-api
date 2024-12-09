<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->repository = self::getContainer()->get(UserRepository::class);
    }

    public function testSaveUser(): void
    {
        $user = new User();
        $user->setName('Jane Doe')
            ->setEmail('jane1@example.com')
            ->setAge(25)
            ->setPassword('securepass');

        // Guarda el usuario y asegura que se persiste correctamente
        $this->repository->saveUser($user);

        // Verifica que el ID del usuario se haya establecido, lo que indica que se guardó en la base de datos
        $this->assertNotNull($user->getId(), 'The user ID should not be null after saving.');
    }

    public function testDeleteUser(): void
    {
        // Crea un nuevo usuario para la prueba
        $user = new User();
        $user->setName('Jane Doe')
            ->setEmail('jane@example.com')
            ->setAge(25)
            ->setPassword('securepass');

        // Guarda el usuario
        $this->repository->saveUser($user);

        // Asegúrate de que el usuario ahora tiene un ID
        $this->assertNotNull($user->getId(), 'The user ID should not be null after saving.');

        // Verifica que el usuario se guarda en la base de datos
        $userFromDb = $this->repository->find($user->getId());
        $this->assertNotNull($userFromDb, 'User should exist in the database before deletion.');

        // Elimina el usuario
        $this->repository->deleteUser($user);

        // Verifica que el usuario ya no existe en la base de datos
        $this->assertNull($this->repository->find($user->getId()), 'The user should not exist after deletion.');
    }
}
