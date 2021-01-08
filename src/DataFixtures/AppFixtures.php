<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Bio;
use App\Entity\Book;
use App\Entity\Media;
use App\Entity\Press;
use App\Entity\SocialMedia;
use App\Entity\Tag;
use App\Entity\User;
use App\Service\SlugifyService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
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
        $faker = Factory::create();

        $user = new User();
        $password = $this->encoder->encodePassword($user, 'password');
        $user->setEmail('user@user.com')
        ->setRoles(["ROLE_ADMIN"])
        ->setPassword($password);
        $manager->persist($user);

        $bio = new Bio();
        $bio->setContent('Alice Pfeiffer, franco-britannique, est journaliste de mode. Collaboratrice notamment pour The Guardian, Vogue International et les Inrocks, elle est également titulaire d’un master en Gender Studies à la London School of Economics. Je ne suis pas Parisienne est son premier livre.')
        ->setImageLink('https://static.lexpress.fr/medias_11465/w_1365,h_764,c_crop,x_0,y_353/w_480,h_270,c_fill,g_north/v1493383606/alice-pfeiffer_5870377.jpg');
        $manager->persist($bio);

        $facebook = new SocialMedia();
        $facebook->setName('facebook')->setLink('https://www.facebook.com/public/Alice-Pfeiffer');
        $manager->persist($facebook);

        $insta = new SocialMedia();
        $insta->setName('instagram')->setLink('https://www.instagram.com/alicepfeiffer/?hl=en');
        $manager->persist($insta);

        $tags = [];
        for ($i = 0; $i < 5; $i++) {
            $tag = new Tag();
            $tag->setTitle($faker->word);
            $manager->persist($tag);
            $tags[] = $tag;
        }

        // Fixtures articles
        for($i = 0; $i < 5; $i++){
            $article = new Article();
            $article->setTitle($faker->catchPhrase())
            ->setLink($faker->url)
            ->setImageLink('https://leprescripteur.prescriptionlab.com/wp-content/uploads/2018/10/Alice-Pfeiffer-femme-de-t%C3%AAte%E2%80%A6-et-de-cheveux-le-prescripteur-interview-prescription-lab-6.jpg')
            ->setImage('5ff6b39e5b863544224463.jpg')
            ->setImageAlt('Image description')
            ->setSlug($this->slugifyService->slugify($article->getTitle()))
            ->addTag($tags[0])
            ->addTag($tags[1])
            ->addTag($tags[2]);
            $manager->persist($article);
        }

        // Fixtures books
        for($i = 0; $i < 5; $i++){
            $book = new Book();
            $book->setTitle($faker->catchPhrase())
            ->setPublishedAt(new \DateTime())
            ->setLink($faker->url)
            ->setImageLink('https://www.lesmissives.fr/wp-content/uploads/2020/04/parisienne.jpg')
            ->setImage('5ff6b39e5b863544224463.jpg')
            ->addTag($tags[1])
            ->addTag($tags[4])
            ->addTag($tags[3])
            ->setImageAlt('Image description')
            ->setSlug($this->slugifyService->slugify($book->getTitle()));
            $manager->persist($book);
        }
        // Fixtures press
        for($i = 0; $i < 5; $i++){
            $press = new Press();
            $press->setMagazine($faker->word)
            ->setLink($faker->url)
            ->setImageLink('https://s3.eu-west-3.amazonaws.com/magazineantidote.com/wp-content/uploads/2016/01/alice-pfeiffer-antidote-bis-2.jpg')
            ->setImage('5ff6b39e5b863544224463.jpg')
            ->addTag($tags[0])
            ->addTag($tags[2])
            ->addTag($tags[3])
            ->setImageAlt('Image description');
            $manager->persist($press);
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
