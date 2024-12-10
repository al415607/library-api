<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Persistir y guardar un usuario
     *
     * @param User $user
     */
    public function saveUser(User $user): void
    {
        if ($this->findOneBy(['email' => $user->getEmail()])) {
            throw new \Exception("El correo electrónico ya está en uso.");
        }

        $this->getEntityManager()->persist($user);  // Usar getEntityManager()
        $this->getEntityManager()->flush();
    }

    /**
     * Eliminar un usuario
     *
     * @param User $user
     */
    public function deleteUser(User $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Buscar un usuario por su correo electrónico
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Buscar usuarios por edad
     *
     * @param int $age
     * @return User[]
     */
    public function findByAge(int $age): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.age = :age')
            ->setParameter('age', $age)
            ->getQuery()
            ->getResult();
    }
}
