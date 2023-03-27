<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Project;
use App\Entity\Tag;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test')]
class TestController extends AbstractController
{
    // le préfixe + l'url de la route => '/test/project'
    #[Route('/project', name: 'app_test_project')]
    public function project(ManagerRegistry $doctrine): Response
    {
        // récupération de l'entity manager
        $em = $doctrine->getManager();

        // récupération du repository d'une entité
        $repository = $doctrine->getRepository(Project::class);

        // récupération d'objets via une requête personnalisée
        // par encadrement de date de début et date de fin
        $dateStart = DateTime::createFromFormat('d/m/Y', '01/03/2023');
        $dateEnd = DateTime::createFromFormat('d/m/Y', '01/05/2023');
        $projects = $repository->findByDeliveryDateBetween($dateStart, $dateEnd);
        dump($projects);

        // récupération d'un objet par id
        $project1 = $repository->find(1);

        if ($project1) {
            // récupération des students associés au projet
            foreach ($project1->getStudents() as $student) {
                $student->setProject(null);
            }

            // suppression d'un objet
            $em->remove($project1);
            $em->flush();
        }

        exit();
    }

    // le préfixe + l'url de la route => '/test/user'
    #[Route('/user', name: 'app_test_user')]
    public function user(UserRepository $repository): Response
    {
        // récupération d'objets via une requête personnalisée
        // tous les users de type student
        $students = $repository->findAllStudents();
        dump($students);

        // récupération d'objets via une requête personnalisée
        // tous les users de type admin
        $admins = $repository->findAllAdmins();
        dump($admins);

        exit();
    } 

    // le préfixe + l'url de la route => '/test/tag'
    #[Route('/tag', name: 'app_test_tag')]
    public function tag(ManagerRegistry $doctrine, TagRepository $repository): Response
    {
        // récupération de l'entity manager
        $em = $doctrine->getManager();

        // récupération d'objets via une requête personnalisée
        // tri par ordre alphabétique de name
        $tags = $repository->findAllOrderByName();
        dump($tags);

        // récupération d'un objet par id
        $tag1 = $repository->find(1);
        dump($tag1);

        // récupération d'un objet par id
        $tag123 = $repository->find(123);
        dump($tag123);

        // récupération d'objets par name
        $tags = $repository->findBy(
            [
                'name' => 'HTML',
            ]
        );
        dump($tags);

        // récupération d'objets via une requête personnalisée
        // par mot clé dans name ou dans description
        $tags = $repository->findByKeyword('laboriosam');
        dump($tags);

        // création d'un nouvel objet
        $tag = new Tag();
        $tag->setName('Tag de test');
        $tag->setDescription('Ce tag est un test');

        // avant enregistrement, l'objet n'a pas d'id
        dump($tag->getId());

        // enregistrement dans la BDD
        $em->persist($tag);
        $em->flush();

        // après enregistrement, l'objet possède un id
        dump($tag->getId());

        // récupération d'un objet par id
        $tag1 = $repository->find(1);

        dump($tag1);

        // modification d'un objet
        $tag1->setName('Un autre nom de tag de test');
        $tag1->setDescription(null);

        // si l'objet est déjà stocké en BDD, il n'est pas nécessaire d'appeler la méthode persist()
        // $em->persist($tag1);
        $em->flush();

        dump($tag1);

        // récupération d'un objet par id
        $tag14 = $repository->find(14);

        // gestion des exceptions
        try {
            // supression d'un objet
            $em->remove($tag14);
            $em->flush();
        } catch (Exception $e) {
            // interception d'une exception
            dump($e->getMessage());
            dump($e->getCode());
            dump($e->getFile());
            dump($e->getLine());
            dump($e->getTraceAsString());
        }

        dump($tag14);

        // récupération d'un objet par id
        $tag1 = $repository->find(1);

        if ($tag1) {
            // récupération des students associés au tag
            foreach ($tag1->getStudents() as $student) {
                dump($student);
            }

            // récupération des projects associés au tag
            foreach ($tag1->getProjects() as $project) {
                dump($project);
            }

            // suppression d'un objet
            $em->remove($tag1);
            $em->flush();
        }

        exit();
    }
}
