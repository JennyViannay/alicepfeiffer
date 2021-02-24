<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Bio;
use App\Entity\Book;
use App\Entity\Language;
use App\Entity\Media;
use App\Entity\Post;
use App\Entity\Press;
use App\Entity\SocialMedia;
use App\Entity\Tag;
use App\Entity\User;
use App\Service\SlugifyService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    private $slugifyService;

    public function __construct(UserPasswordEncoderInterface $encoder, SlugifyService $slugifyService)
    {
        $this->encoder = $encoder;
        $this->slugifyService = $slugifyService;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        $user = new User();
        $password = $this->encoder->encodePassword($user, 'password');
        $user->setEmail('user@user.com')
        ->setRoles(["ROLE_ADMIN"])
        ->setPassword($password);
        $manager->persist($user);

        // Fixtures BIO
        $bio = new Bio();
        $bio->setContent('Alice Pfeiffer, franco-britannique, est journaliste de mode. Collaboratrice notamment pour The Guardian, Vogue International et les Inrocks, elle est également titulaire d’un master en Gender Studies à la London School of Economics. Je ne suis pas Parisienne est son premier livre.')
        ->setImageLink('https://static.lexpress.fr/medias_11465/w_1365,h_764,c_crop,x_0,y_353/w_480,h_270,c_fill,g_north/v1493383606/alice-pfeiffer_5870377.jpg');
        $manager->persist($bio);

        // Fixtures Social Media
        $facebook = new SocialMedia();
        $facebook->setName('facebook')->setLink('https://www.facebook.com/public/Alice-Pfeiffer');
        $manager->persist($facebook);

        $insta = new SocialMedia();
        $insta->setName('instagram')->setLink('https://www.instagram.com/alicepfeiffer/?hl=en');
        $manager->persist($insta);

        // Fictures lang
        $fr = new Language();
        $fr->setLang('fr');
        $manager->persist($fr);

        $en = new Language();
        $en->setLang('en');
        $manager->persist($en);

        $tags = [];
        for ($i = 0; $i < 5; $i++) {
            $tag = new Tag();
            $tag->setTitle($faker->word);
            $manager->persist($tag);
            $tags[] = $tag;
        }

        // Fixtures posts
        for($i = 0; $i < 5; $i++){
            $post = new Post();
            $post->setTitle($faker->catchPhrase())
            ->setContent($faker->paragraph(1000))
            ->setSlug($this->slugifyService->slugify($post->getTitle()))
            ->addTag($tags[0])
            ->addTag($tags[1])
            ->addTag($tags[2])
            ->setLang($fr)
            ->setReadingTime(10);
            $manager->persist($post);
        }

        // Fixtures press
        $magazines = [];
        for($i = 0; $i < 2; $i++){
            $press = new Press();
            $press->setMagazine($faker->word)
            ->setLink($faker->url)
            ->setImageLink('https://ibb.co/4gXW7TL')
            ->setImage('5ff8396a4d1b7193122451.png')
            ->addTag($tags[0])
            ->addTag($tags[2])
            ->addTag($tags[3])
            ->setImageAlt('Image description')
            ->setSlug($this->slugifyService->slugify($press->getMagazine()));;
            $manager->persist($press);
            $magazines[] = $press;
        }

        // Fixtures articles
        for($i = 0; $i < 5; $i++){
            $article = new Article();
            $article->setTitle($faker->catchPhrase())
            ->setLink($faker->url)
            ->setImageLink('https://ibb.co/4gXW7TL')
            ->setImage('5ff8396a4d1b7193122451.png')
            ->setImageAlt('Image description')
            ->setSlug($this->slugifyService->slugify($article->getTitle()))
            ->setMagazine($faker->randomElement($magazines))
            ->addTag($tags[0])
            ->addTag($tags[1])
            ->addTag($tags[2])
            ->setLang($fr);
            $manager->persist($article);
        }

        // Fixtures books
        for($i = 0; $i < 2; $i++){
            $book = new Book();
            $book->setTitle($faker->catchPhrase())
            ->setPublishedAt(new \DateTime())
            ->setLink($faker->url)
            ->setImageLink('https://ibb.co/HnG3ydG')
            ->setImage('5ff844c252770108354429.png')
            ->addTag($tags[1])
            ->addTag($tags[4])
            ->addTag($tags[3])
            ->setDescription($faker->paragraph())
            ->setImageAlt('Image description')
            ->setSlug($this->slugifyService->slugify($book->getTitle()));
            $manager->persist($book);
        }

        for($i = 0; $i < 5; $i++){
            $media = new Media();
            $media->setTitle($faker->catchPhrase())
            ->setSlug($this->slugifyService->slugify($book->getTitle()))
            ->setEmbedVideo('https://www.youtube.com/embed/w1wzNkSZ7zs')
            ->addTag($tags[0])
            ->addTag($tags[2])
            ->addTag($tags[1]);
            $manager->persist($media);
        }

        $manager->flush();
    }
}
