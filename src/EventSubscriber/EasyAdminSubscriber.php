<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use App\Entity\Bio;
use App\Entity\Book;
use App\Entity\Media;
use App\Entity\Post;
use App\Entity\Press;
use App\Service\SlugifyService;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $slugger;

    public function __construct(SlugifyService $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setEntitiesPrePersist']
        ];
    }

    public function setEntitiesPrePersist(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Article || $entity instanceof Book || $entity instanceof Media || $entity instanceOf Post) {
            $slug = $this->slugger->slugify($entity->getTitle());
            $entity->setSlug($slug);
            if ($entity instanceof Media) {
                    $linkToEmbed = $entity->getEmbedVideo();
                    $first = explode(' ', $linkToEmbed);
                    $string = str_replace("src=\"", "", $first[3]);
                    $finalString = str_replace("\"", "", $string);
                    $entity->setEmbedVideo($finalString);
            }
        }
        if (
            $entity instanceof Article
            || $entity instanceof Bio
            || $entity instanceof Book
            || $entity instanceof Press
        ) {
            if (empty($entity->getImageAlt())) {
                $entity->setImageAlt('Image description');
            }
            if (empty($entity->getImageFile()) && empty($entity->getImageLink())) {
                $entity->setImageLink('https://static.lexpress.fr/medias_11465/w_1365,h_764,c_crop,x_0,y_353/w_480,h_270,c_fill,g_north/v1493383606/alice-pfeiffer_5870377.jpg');
            }
        }
        return;
    }
}
