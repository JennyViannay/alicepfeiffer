<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Post;
use App\Entity\Press;
use App\Form\ContactType;
use App\Repository\ArticleRepository;
use App\Repository\BioRepository;
use App\Repository\BookRepository;
use App\Repository\LegalMentionRepository;
use App\Repository\MediaRepository;
use App\Repository\PressRepository;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Repository\PostLikeRepository;
use App\Service\InstagramService;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    private $bioRepository;
    private $articleRepository;
    private $pressRepository;
    private $bookRepository;
    private $mediaRepository;
    private $tagRepository;
    private $postRepository;
    private $legalMentionRepository;
    private $mailerService;
    private $instaService;

    public function __construct(
        BioRepository $bioRepository,
        ArticleRepository $articleRepository,
        PressRepository $pressRepository,
        BookRepository $bookRepository,
        MediaRepository $mediaRepository,
        TagRepository $tagRepository,
        PostRepository $postRepository,
        LegalMentionRepository $legalMentionRepository,
        MailerService $mailerService,
        InstagramService $instaService
    ) {
        $this->bioRepository = $bioRepository;
        $this->articleRepository = $articleRepository;
        $this->pressRepository = $pressRepository;
        $this->bookRepository = $bookRepository;
        $this->mediaRepository = $mediaRepository;
        $this->tagRepository = $tagRepository;
        $this->postRepository = $postRepository;
        $this->legalMentionRepository = $legalMentionRepository;
        $this->mailerService = $mailerService;
        $this->instaService = $instaService;
    }
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->render('pages/index.html.twig', [
            'bio' => $this->bioRepository->findAll()[0],
            'articles' => $this->articleRepository->findAll(),
            'posts' => $this->postRepository->findAll(),
            'presses' => $this->pressRepository->findAll(),
            'books' => $this->bookRepository->findAll()
        ]);
    }

    /**
     * @Route("/contact", name="app_contact", methods={"GET","POST"})
     */
    public function contact(Request $request): Response
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

        return $this->render('pages/contact/contact.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/posts/{tag}", name="app_posts", methods={"GET"})
     */
    public function posts(string $tag = null): Response
    {
        $posts = $this->postRepository->findAll();
        $byTag = [];
        if ($tag) {
            $tag = $this->tagRepository->findOneBy(["title" => $tag]);
            $byTag = $tag->getPosts();
        }
        return $this->render('pages/posts/posts.html.twig', [
            'posts' => $byTag ? $byTag : $posts,
            'tags' => $this->tagRepository->findAll()
        ]);
    }

    /**
     * @Route("/read/{slug}", name="app_post", methods={"GET"})
     */
    public function post(Post $post): Response
    {
        $suggestions = [];
        $tags = $post->getTags();

        for ($i = 0 ; $i < count($tags) ; $i++) {
            $posts = $tags[$i]->getPosts();
            if($posts[$i]) {
                if ($posts[$i]->getId() !== $post->getId()) {
                    $suggestions[] = $posts[$i];
                }
            }
        }
        
        return $this->render('pages/posts/show_post.html.twig', [
            'post' => $post,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * @Route("/articles/{tag}", name="app_articles", methods={"GET"})
     */
    public function articles(string $tag = null): Response
    {
        $articles = $this->articleRepository->findAll();
        $byTag = [];
        if ($tag) {
            $tag = $this->tagRepository->findOneBy(["title" => $tag]);
            $byTag = $tag->getArticles();
        }
        return $this->render('pages/articles/articles.html.twig', [
            'articles' => $byTag ? $byTag : $articles,
            'tags' => $this->tagRepository->findAll()
        ]);
    }

    /**
     * @Route("/books", name="app_books", methods={"GET"})
     */
    public function books(): Response
    {
        return $this->render('pages/books/books.html.twig', [
            'books' => $this->bookRepository->findAll()
        ]);
    }

    /**
     * @Route("/press", name="app_press", methods={"GET"})
     */
    public function press(): Response
    {
        return $this->render('pages/press/press.html.twig', [
            'presses' => $this->pressRepository->findAll()
        ]);
    }

    /**
     * @Route("/press/{slug}", name="app_press_show", methods={"GET"})
     */
    public function showPress(Press $press): Response
    {
        return $this->render('pages/press/show_press.html.twig', [
            'press' => $press
        ]);
    }

    /**
     * @Route("/medias", name="app_medias", methods={"GET"})
     */
    public function medias(): Response
    {
        return $this->render('pages/medias/medias.html.twig', [
            'medias' => $this->mediaRepository->findAll()
        ]);
    }

    /**
     * @Route("/follow-me", name="app_follow_me", methods={"GET"})
     */
    public function followMe(): Response
    {
        return $this->render('pages/instagram/follow_me.html.twig', [
            'insta' => $this->instaService->getInfosInstagramAccount(),
            'lastPosts' => $this->instaService->getLast12Posts()
        ]);
    }

    /**
     * @Route("/legal-mentions", name="app_mentions", methods={"GET"})
     */
    public function mentions(): Response
    {
        return $this->render('pages/annexes/legal_mention.html.twig', [
            'mentions' => $this->legalMentionRepository->findAll()
        ]);
    }

    /**
     * @Route("/search", name="app_search", methods={"GET"})
     */
    public function search(Request $request): Response
    {
        $query = $request->query->get('q');

        $results = [];
        if (null !== $query) {
            $results = $this->tagRepository->findBy(['title' => $query]);
        }

        return $this->render('pages/filter/result_search.html.twig', [
            'results' => $results,
        ]);
    }
}
