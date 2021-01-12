<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Bio;
use App\Entity\Book;
use App\Entity\Contact;
use App\Entity\Image;
use App\Entity\Media;
use App\Entity\Press;
use App\Entity\SocialMedia;
use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(ArticleCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Alice Pfeiffer - Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linktoRoute('Site', 'far fa-window-maximize', 'home');
        yield MenuItem::section('Contact');
        yield MenuItem::linkToCrud('Messages', 'fas fa-envelope', Contact::class);
        yield MenuItem::section('Content');
        yield MenuItem::linkToCrud('Bio', 'far fa-user', Bio::class);
        yield MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Press', 'far fa-newspaper', Press::class);
        yield MenuItem::linkToCrud('Books', 'fas fa-book', Book::class);
        yield MenuItem::linkToCrud('Medias', 'fab fa-youtube', Media::class);
        yield MenuItem::section('Social Medias');
        yield MenuItem::linkToCrud('Links', 'fas fa-share', SocialMedia::class);
        yield MenuItem::linkToUrl('facebook', 'fab fa-facebook', 'https://www.facebook.com/public/Alice-Pfeiffer')
        ->setLinkTarget('_blank');
        yield MenuItem::linkToUrl('instagram', 'fab fa-instagram', 'https://www.instagram.com/alicepfeiffer/?hl=en')
        ->setLinkTarget('_blank');
        yield MenuItem::section('SEO');
        yield MenuItem::linkToCrud('Tags', 'fas fa-hashtag', Tag::class);
    }
}
