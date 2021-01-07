<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Bio;
use App\Entity\SocialMedia;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $user = new User();
        $user->setEmail('user@user.com');
        $user->setRoles(["ROLE_ADMIN"]);
        $password = $this->encoder->encodePassword($user, 'password');
        $user->setPassword($password);
        $manager->persist($user);

        $bio = new Bio();
        $bio->setContent('Alice Pfeiffer, franco-britannique, est journaliste de mode. Collaboratrice notamment pour The Guardian, Vogue International et les Inrocks, elle est également titulaire d’un master en Gender Studies à la London School of Economics. Je ne suis pas Parisienne est son premier livre.');
        $bio->setImageLink('https://static.lexpress.fr/medias_11465/w_1365,h_764,c_crop,x_0,y_353/w_480,h_270,c_fill,g_north/v1493383606/alice-pfeiffer_5870377.jpg');
        $manager->persist($bio);

        $facebook = new SocialMedia();
        $facebook->setName('Facebook')->setLink('https://www.facebook.com/public/Alice-Pfeiffer');
        $manager->persist($facebook);
        $insta = new SocialMedia();
        $insta->setName('Instagram')->setLink('https://www.instagram.com/alicepfeiffer/?hl=en');
        $manager->persist($insta);

        // TODO: Fixtures book / press / article
        // for($i = 0; $i < 5; $i++)
        // {
        //     $article = new Article();
        //     $article->setTitle($faker->catchPhrase());

        // }

        $manager->flush();
    }
}
