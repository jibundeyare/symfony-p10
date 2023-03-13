<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// préfixe de route, toutes les URL des routes de la classe devront être démarrer par le préfixe pour être reconnues
#[Route('/hello')]
class HelloController extends AbstractController
{
    // la route associe un verbe HTTP (GET par défaut) et une URL à la fonction qui est juste en-dessous
    #[Route('/hello', name: 'app_hello_index')]
    public function index(): Response
    {
        // le rendu de la vue est renvoyé au client web
        return $this->render('hello/index.html.twig', [
            // transmission de variables au template twig
            'controller_name' => 'Foo Bar Baz',
        ]);
    }

    // une URL comprenant un paramètre envoyé par l'utilisateur
    // la fonction doit accepter un parmètre qui porte le même nom que le paramètre de l'URL
    // il est possible de filtrer les données en rajoutant un type hinting  
    #[Route('/age/{birthYear}', name: 'app_hello_age', methods: ['GET'])]
    public function age(int $birthYear): Response
    {
        // traitement de données
        $year = 2023;
        $age = $year - $birthYear;

        return $this->render('hello/age.html.twig', [
            'birthYear' => $birthYear,
            'year' => $year,
            'age' => $age,
        ]);
    }
}
