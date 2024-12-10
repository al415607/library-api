<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Exception\ORMException;


class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Persiste un libro en la base de datos.
     *
     * @param Book $book
     * @throws ORMException
     */
    public function save(Book $book): void
    {
        $entityManager = $this->getEntityManager();
        try {
            $entityManager->persist($book);
            $entityManager->flush();
        } catch (ORMException $e) {
            throw new \RuntimeException('Error al guardar el libro: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un libro de la base de datos.
     *
     * @param Book $book
     * @throws ORMException
     */
    public function delete(Book $book): void
    {
        $entityManager = $this->getEntityManager();
        try {
            $entityManager->remove($book);
            $entityManager->flush();
        } catch (ORMException $e) {
            throw new \RuntimeException('Error al eliminar el libro: ' . $e->getMessage());
        }
    }
}
