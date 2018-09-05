<?php

namespace App\Controller;

use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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

        $form = $this->createFormBuilder($author)
            ->add('name', TextType::class, ['label' => 'Имя'])
            ->add('midname', TextType::class, ['label' => 'Отчество'])
            ->add('surname', TextType::class, ['label' => 'Фамилия'])
            ->add('submit', SubmitType::class, ['label' => 'Добавить автора'])
            ->getForm();

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
        ]);
    }
}
