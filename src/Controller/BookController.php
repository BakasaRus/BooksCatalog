<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="book")
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
        $form = $this->createFormBuilder($book)
            ->add('title', TextType::class, ['label' => 'Название книги'])
            ->add('publicationYear', IntegerType::class)
            ->add('isbn', TextType::class)
            ->add('pageCount', IntegerType::class)
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'surname',
                'multiple' => true
            ])
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $book = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirectToRoute('book');
        }

        return $this->render('book/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
