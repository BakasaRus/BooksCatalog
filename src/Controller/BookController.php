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
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
        $form = $this->createFormBuilder($book)
            ->add('title', TextType::class, ['label' => 'Название книги'])
            ->add('publicationYear', IntegerType::class)
            ->add('isbn', TextType::class)
            ->add('pageCount', IntegerType::class)
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'fullname',
                'multiple' => true,
                'required' => false
            ])
            ->add('cover', FileType::class, ['required' => false, 'data_class' => null])
            ->add('submit', SubmitType::class)
            ->getForm();

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
        $book->setCover(
            new File($this->getParameter('covers_directory').'/'.$book->getCover())
        );

        $form = $this->createFormBuilder($book)
            ->add('title', TextType::class, ['label' => 'Название книги'])
            ->add('publicationYear', IntegerType::class)
            ->add('isbn', TextType::class)
            ->add('pageCount', IntegerType::class)
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'surname',
                'multiple' => true,
                'required' => false
            ])
            ->add('cover', FileType::class, ['required' => false, 'data_class' => null])
            ->add('submit', SubmitType::class)
            ->getForm();

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
