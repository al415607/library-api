<?php

namespace App\Tests\Repository;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BookRepositoryTest extends KernelTestCase
{
    private BookRepository $bookRepository;
    private $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->bookRepository = self::getContainer()->get(BookRepository::class);
        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
    }

    public function testSaveBook(): void
    {
        $book = new Book();
        $book->setTitle('Test Book')
            ->setAuthor('Test Author')
            ->setGenre('Fiction')
            ->setYear(2024);

        $this->entityManager->persist($book);
        $this->entityManager->flush(); // Asegúrate de que los cambios se persisten en la base de datos
        $this->assertNotNull($book->getId());
    }

    public function testDeleteBook(): void
{
    // Crear un libro y persistirlo
    $book = new Book();
    $book->setTitle('Test Book')
        ->setAuthor('Test Author')
        ->setGenre('Fiction')
        ->setYear(2024);

    $this->entityManager->persist($book);
    $this->entityManager->flush(); // Asegúrate de que el libro se persiste

    // Verifica que el libro tiene un ID asignado
    $this->assertNotNull($book->getId(), 'The book should have an ID after being saved.');

    // Elimina el libro
    $this->entityManager->remove($book);
    $this->entityManager->flush(); // Asegúrate de que el libro se elimina

    // Verifica que el libro ya no exista en la base de datos
    $deletedBook = $this->bookRepository->find($book->getId());
    $this->assertNull($deletedBook, 'The book should not exist in the database after deletion.');
}




    public function testGetAllBooks(): void
    {
        $book = new Book();
        $book->setTitle('Test Book 2')
            ->setAuthor('Test Author 2')
            ->setGenre('Non-Fiction')
            ->setYear(2025);

        $this->bookRepository->save($book);

        $books = $this->bookRepository->findAll();
        $this->assertGreaterThan(0, count($books));
        $this->assertSame('Test Book 2', $books[1]->getTitle());
    }

    public function testUpdateBook(): void
    {
        // Crear un libro
        $book = new Book();
        $book->setTitle('Old Title')
            ->setAuthor('Old Author')
            ->setGenre('Old Genre')
            ->setYear(2020);

        $this->bookRepository->save($book);

        // Actualizar título
        $book->setTitle('New Title');
        $this->bookRepository->save($book);

        // Verificar que el libro fue actualizado
        $updatedBook = $this->bookRepository->find($book->getId());
        $this->assertSame('New Title', $updatedBook->getTitle());
    }
}
