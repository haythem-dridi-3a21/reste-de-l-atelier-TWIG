<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// #[Route('author')]
class AuthorController extends AbstractController
{
    public $authors = array(
        array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => '/images/william-shakespeare.jpg', 'username' => ' William Shakespeare', 'email' => ' william.shakespeare@gmail.com', 'nb_books' => 200),
        array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
    );

    #[Route('/author/{id}', name: 'show_author')]
    public function authorDetails(String $id): Response
    {
        $author = array_filter($this->authors, function ($u) use ($id) {
            return $u['id'] = $id;
        });

        return $this->render('author/showAuthor.html.twig', [
            'author' => $author[0],
        ]);
    }

    #[Route('/author')]
    public function index(): Response
    {
        $nb_book_totals = 0;

        for ($i = 0; $i < count($this->authors); $i++) {
            $nb_book_totals += $this->authors[$i]['nb_books'];
        }

        return $this->render('author/list.html.twig', [
            'authors' => $this->authors,
            'nb_book_totals' => $nb_book_totals,
        ]);
    }

}
