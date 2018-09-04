<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }

    /**
     * @Route("/books/{name}", name="books_show")
     */
    public function show($name)
    {
        return $this->render('homepage/book.html.twig', [
            'name' => $name
        ]);
    }
}
