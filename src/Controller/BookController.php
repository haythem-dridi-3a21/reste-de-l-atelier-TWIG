<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function affiche(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        return $this->render('book/read.html.twig', [
            'books' => $books,
        ]);
    }
}
