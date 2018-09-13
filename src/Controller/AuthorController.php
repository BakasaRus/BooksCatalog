<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author", name="author")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Author::class);
        $authors = $repo->findAll();
        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    /**
     * @Route("/author/create", name="author_create")
     */
    public function create(Request $request)
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author)
            ->add('submit', SubmitType::class, [
                'label' => 'form.create'
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $author = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($author);
            $entityManager->flush();

            return $this->redirectToRoute('author');
        }
        
        return $this->render('author/create.html.twig', [
            'form' => $form->createView(),
            'title' => 'form.author.create'
        ]);
    }

    /**
     * @Route("/author/{id}", name="author_show")
     */
    public function show(Author $author)
    {
        return $this->render('author/show.html.twig', [
            'author' => $author
        ]);
    }

    /**
     * @Route("/author/{id}/edit", name="author_edit")
     */
    public function edit(Author $author, Request $request)
    {
        $form = $this->createForm(AuthorType::class, $author)
            ->add('submit', SubmitType::class, [
                'label' => 'form.update'
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $author = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($author);
            $entityManager->flush();

            return $this->redirectToRoute('author');
        }
        
        return $this->render('author/create.html.twig', [
            'form' => $form->createView(),
            'title' => 'form.author.update'
        ]);
    }

    /**
     * @Route("/author/{id}/remove", name="author_remove")
     */
    public function remove(Author $author)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($author);
        $entityManager->flush();
        return $this->redirectToRoute('author');
    }
}
