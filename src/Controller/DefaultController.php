<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\BioRepository;
use App\Repository\BookRepository;
use App\Repository\PressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private $bioRepository;
    private $articleRepository;
    private $pressRepository;
    private $bookRepository;

    public function __construct(
        BioRepository $bioRepository, 
        ArticleRepository $articleRepository, 
        PressRepository $pressRepository, 
        BookRepository $bookRepository
    )
    {
        $this->bioRepository = $bioRepository;
        $this->articleRepository = $articleRepository;
        $this->pressRepository = $pressRepository;
        $this->bookRepository = $bookRepository;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'bio' => $this->bioRepository->findAll()[0],
            'articles' => $this->articleRepository->findAll(),
            'presses' => $this->pressRepository->findAll(),
            'books' => $this->bookRepository->findAll()
        ]);
    }

    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact(): Response
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           // TODO : send mail aliceisyourfriend@gmail.com
           dd($_POST); die;
        }
        return $this->render('default/contact.html.twig');
    }

    /**
     * @Route("/articles", name="app_articles")
     */
    public function articles(): Response
    {
        return $this->render('default/articles.html.twig', [
            'articles' => $this->articleRepository->findAll()
        ]);
    }

    /**
     * @Route("/books", name="app_books")
     */
    public function books(): Response
    {
        return $this->render('default/books.html.twig', [
            'books' => $this->bookRepository->findAll()
        ]);
    }

    /**
     * @Route("/press", name="app_press")
     */
    public function press(): Response
    {
        return $this->render('default/press.html.twig', [
            'presses' => $this->pressRepository->findAll()
        ]);
    }
}
