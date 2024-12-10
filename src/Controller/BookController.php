<?php
namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/books/{id}", name="update_book", methods={"PUT"})
     */
    public function updateBook(int $id, Request $request, ValidatorInterface $validator, AuthorizationCheckerInterface $authChecker): JsonResponse
    {
        if (!$this->getUser() || !$authChecker->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('Acceso denegado');
        }

        $book = $this->entityManager->getRepository(Book::class)->find($id);
        
        if (!$book) {
            throw new NotFoundHttpException('Libro no encontrado');
        }

        $book->setTitle($request->request->get('title'));
        $book->setAuthor($request->request->get('author'));
        $book->setGenre($request->request->get('genre'));
        $book->setYear($request->request->get('year'));

        $errors = $validator->validate($book);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Persistir cambios
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Libro actualizado con éxito'], Response::HTTP_OK);
    }

    /**
     * @Route("/books/{id}", name="delete_book", methods={"DELETE"})
     */
    public function deleteBook(int $id, AuthorizationCheckerInterface $authChecker): JsonResponse
    {
        if (!$this->getUser() || !$authChecker->isGranted('ROLE_USER')) {
            throw new AccessDeniedException('Acceso denegado');
        }

        $book = $this->entityManager->getRepository(Book::class)->find($id);
        
        if (!$book) {
            throw new NotFoundHttpException('Libro no encontrado');
        }

        $this->entityManager->remove($book);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Libro eliminado con éxito'], Response::HTTP_OK);
    }
}
