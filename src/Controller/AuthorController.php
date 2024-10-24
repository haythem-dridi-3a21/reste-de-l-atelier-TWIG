<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
            return $u['id'] == $id;
        });

        return $this->render('author/showAuthor.html.twig', [
            'author' => reset($author),
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

    #[Route('/afficher', name: 'affichier_all')]
    public function afficher(AuthorRepository $repository): Response
    {
        $authors = $repository->findAll();

        return $this->render('author/authors-list.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/delete_author/{id}', name: 'delete_author')]
    public function delete($id, AuthorRepository $repository, ManagerRegistry $doctrine)
    {
        $author = $repository->find($id);

        $em = $doctrine->getManager();

        $em->remove($author);

        $em->flush();

        return $this->redirectToRoute('affichier_all');
    }

    #[Route('/create_author', name: 'create_author')]
    public function creatrAuthor(ManagerRegistry $doctrine, Request $request)
    {
        $author = new Author();

        // $author->setUsername("haythem");

        // $author->setEmail("haythem@gmail.com");

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();

            $em->persist($author);

            $em->flush();

            return $this->redirectToRoute('affichier_all');
        }

        return $this->render('author/add-author.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update_author/{id}', name: 'update_author')]
    public function updateAuthor($id, AuthorRepository $repository, ManagerRegistry $doctrine, Request $request)
    {
        $author = $repository->find($id);

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();

            $em->flush();

            return $this->redirectToRoute('affichier_all');
        }

        return $this->render('author/update-author.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
