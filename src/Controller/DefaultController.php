<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ArticleRepository;
use App\Repository\BioRepository;
use App\Repository\BookRepository;
use App\Repository\MediaRepository;
use App\Repository\PressRepository;
use App\Repository\TagRepository;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private $bioRepository;
    private $articleRepository;
    private $pressRepository;
    private $bookRepository;
    private $mediaRepository;
    private $tagRepository;
    private $mailerService;

    public function __construct(
        BioRepository $bioRepository,
        ArticleRepository $articleRepository,
        PressRepository $pressRepository,
        BookRepository $bookRepository,
        MediaRepository $mediaRepository,
        TagRepository $tagRepository,
        MailerService $mailerService
    ) {
        $this->bioRepository = $bioRepository;
        $this->articleRepository = $articleRepository;
        $this->pressRepository = $pressRepository;
        $this->bookRepository = $bookRepository;
        $this->mediaRepository = $mediaRepository;
        $this->tagRepository = $tagRepository;
        $this->mailerService = $mailerService;
    }
    /**
     * @Route("/", name="home", methods={"GET"})
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
     * @Route("/contact", name="app_contact", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();
            $this->mailerService->sendEmailAfterContact($contact);
            $this->addFlash('success', 'Thank you, your message has been sent!');
            return $this->redirectToRoute('home');
        }

        return $this->render('default/contact.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/captchaverify", name="app_captchaverify", methods={"POST", "GET"})
     */
    public function captchaverify(Request $request): Response
    {
        $recaptcha = $request->getContent();
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "secret" => "6LfJMCQaAAAAAKNzSiCfPTsDoKj_-mf9kWbP2PLQ", "response" => $recaptcha
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);
        return $this->json($data->success, 200);
    }

    /**
     * @Route("/articles", name="app_articles", methods={"GET"})
     */
    public function articles(): Response
    {
        return $this->render('default/articles.html.twig', [
            'articles' => $this->articleRepository->findAll()
        ]);
    }

    /**
     * @Route("/books", name="app_books", methods={"GET"})
     */
    public function books(): Response
    {
        return $this->render('default/books.html.twig', [
            'books' => $this->bookRepository->findAll()
        ]);
    }

    /**
     * @Route("/press", name="app_press", methods={"GET"})
     */
    public function press(): Response
    {
        return $this->render('default/press.html.twig', [
            'presses' => $this->pressRepository->findAll()
        ]);
    }

    /**
     * @Route("/medias", name="app_medias", methods={"GET"})
     */
    public function medias(): Response
    {
        return $this->render('default/medias.html.twig', [
            'medias' => $this->mediaRepository->findAll()
        ]);
    }

    /**
     * @Route("/search", name="app_search", methods={"GET"})
     * @return Response
     */
    public function search(Request $request): Response
    {
        $query = $request->query->get('q');

        $results = [];
        if (null !== $query) {
            $results = $this->tagRepository->findBy(['title' => $query]);
        }

        return $this->render('default/result_search.html.twig', [
            'results' => $results,
        ]);
    }

    /**
     * @Route("/autocomplete", name="app_autocomplete", methods={"GET"})
     * @return Response
     */
    public function autocomplete(Request $request): Response
    {
        $query = $request->query->get('q');

        $results = [];
        if (null !== $query) {
            $results = $this->tagRepository->findByQuery($query);
        }

        return new JsonResponse($results, 200);
    }
}
