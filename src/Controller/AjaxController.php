<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostLike;
use App\Repository\PostLikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SocialMediaRepository;
use App\Repository\TagRepository;

/**
 * @Route("/ajax")
 */
class AjaxController extends AbstractController
{
    /**
     * @Route("/autocomplete", name="app_autocomplete", methods={"GET"})
     * @return Response
     */
    public function autocomplete(Request $request, TagRepository $tagRepository): Response
    {
        $query = $request->query->get('q');

        $results = [];
        if (null !== $query) {
            $results = $tagRepository->findByQuery($query);
        }

        return new JsonResponse($results, 200);
    }

    /**
     * @Route("/social-medias", name="app_social_medias", methods={"GET"})
     */
    public function socialMedias(SocialMediaRepository $socialMedias)
    {
        $sm = $socialMedias->findAll();

        return $this->json($sm, 200);
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
     * @Route("/post/{id}/like", name="app_post_like", methods={"GET","POST"})
     */
    public function postLike(Post $post, Request $request, PostLikeRepository $postLikeRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $ipClient = $request->getClientIp();

        if (!$ipClient) {
            return $this->json(['message' => 'Unauthorized'], 403);
        }

        if ($post->isLikedByClient($ipClient)) {
            $like = $postLikeRepository->findOneBy(['post' => $post, 'ipClient' => $ipClient]);
            $em->remove($like);
            $em->flush();
            return $this->json(['message' => 'Unliked', 'likes' => $postLikeRepository->count(['post' => $post])], 200);
        }

        $like = new PostLike();
        $like->setPost($post)->setIpClient($ipClient);
        $em->persist($like);
        $em->flush();

        return $this->json(['message' => 'Liked', 'likes' => $postLikeRepository->count(['post' => $post])], 200);
    }
}
