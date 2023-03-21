<?php

namespace App\Controller;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test')]
class TestController extends AbstractController
{
    // le préfixe + l'url de la route => '/test/tag'
    #[Route('/tag', name: 'app_test')]
    public function tag(TagRepository $repository): Response
    {
        $tags = $repository->findAllOrderByName();
        dump($tags);

        // recherche par id
        $tag1 = $repository->find(1);
        dump($tag1);

        // recherche par id
        $tag123 = $repository->find(123);
        dump($tag123);

        // recherche par name
        $tags = $repository->findBy(
            [
                'name' => 'HTML',
            ]
        );
        dump($tags);

        // recherche par mot clé dans name ou dans description
        $tags = $repository->findByKeyword('laboriosam');
        dump($tags);

        exit();
    }
}