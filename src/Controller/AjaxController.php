<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SocialMediaRepository;
use App\Repository\TagRepository;

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
}