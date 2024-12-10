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
        $this->entityManager->flush();
        $this->assertNotNull($book->getId());
    }

    public function testDeleteBook(): void
{
    $book = new Book();
    $book->setTitle('Test Book')
        ->setAuthor('Test Author')
        ->setGenre('Fiction')
        ->setYear(2024);

    $this->entityManager->persist($book);
    $this->entityManager->flush();

    
    $this->assertNotNull($book->getId(), 'El libro debe tener un ID tras ser guardado');

   
    $this->entityManager->remove($book);
    $this->entityManager->flush();

    $deletedBook = $this->bookRepository->find($book->getId());
    $this->assertNull($deletedBook, 'El libro no debe estar en la BBDD tras ser eliminado');
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
        $book = new Book();
        $book->setTitle('Old Title')
            ->setAuthor('Old Author')
            ->setGenre('Old Genre')
            ->setYear(2020);

        $this->bookRepository->save($book);

        $book->setTitle('New Title');
        $this->bookRepository->save($book);

        $updatedBook = $this->bookRepository->find($book->getId());
        $this->assertSame('New Title', $updatedBook->getTitle());
    }
}
