<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="book")
     * @Route("/", name="homepage")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Book::class);
        $books = $repo->findAll();
        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * @Route("/book/create", name="book_create")
     */
    public function create(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $file = $form['cover']->getData();
            $coverName = 'placeholder.jpg';
            if (!is_null($file))
            {
                $coverName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('covers_directory'),
                    $coverName
                );
            }

            $book = $form->getData();
            $book->setCover($coverName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('book');
        }

        return $this->render('book/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/book/{id}", name="book_show")
     */
    public function show(Book $book)
    {
        return $this->render('book/show.html.twig', [
            'book' => $book
        ]);
    }

    /**
     * @Route("/book/{id}/edit", name="book_edit")
     */
    public function edit(Book $book, Request $request)
    {
        $coverName = $book->getCover();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $file = $form['cover']->getData();
            if (!is_null($file))
            {
                $coverName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('covers_directory'),
                    $coverName
                );
            }

            $book = $form->getData();
            $book->setCover($coverName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();
            
            return $this->redirectToRoute('book');
        }

        return $this->render('book/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/book/{id}/remove", name="book_remove")
     */
    public function remove(Book $book)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($book);
        $entityManager->flush();
        return $this->redirecttoRoute("book");
    }
}
