<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use App\Form\BookType;
use App\Service\FileUploader;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    public function create(Request $request, FileUploader $fileUploader)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book)
            ->add('submit', SubmitType::class, [
                'label' => 'form.create'
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $file = $form['cover']->getData();
            $coverName = $fileUploader->upload($file);
            $book = $form->getData();
            $book->setCover($coverName);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirectToRoute('book_show', ['id' => $book->getId()]);
        }

        return $this->render('form.html.twig', [
            'form' => $form->createView(),
            'title' => 'form.book.create'
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
    public function edit(Book $book, Request $request, FileUploader $fileUploader)
    {
        $oldCoverName = $book->getCover();
        $form = $this->createForm(BookType::class, $book)
            ->add('submit', SubmitType::class, [
                'label' => 'form.update'
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $file = $form['cover']->getData();
            $coverName = $fileUploader->upload($file, $oldCoverName);
            $book->setCover($coverName);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('book_show', ['id' => $book->getId()]);
        }

        return $this->render('form.html.twig', [
            'form' => $form->createView(),
            'title' => 'form.book.update'
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
