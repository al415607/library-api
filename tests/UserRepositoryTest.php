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

        $this->repository->saveUser($user);

        $this->assertNotNull($user->getId(), 'The user ID should not be null after saving.');
    }

    public function testDeleteUser(): void
    {
        $user = new User();
        $user->setName('Jane Doe')
            ->setEmail('jane@example.com')
            ->setAge(25)
            ->setPassword('securepass');

        $this->repository->saveUser($user);

        $this->assertNotNull($user->getId(), 'El ID del usuario no debe ser null tras guardarse');

        $userFromDb = $this->repository->find($user->getId());
        $this->assertNotNull($userFromDb, 'El usuario debe existir en la BBDD antes de ser eliminado');

        $this->repository->deleteUser($user);

        $this->assertNull($this->repository->find($user->getId()), 'El usuario no debe existir tras ser eliminado');
    }
}
