<?php

namespace App\DataFixtures;

use DateTime;
use DateTimeImmutable;
use App\Entity\Project;
use App\Entity\SchoolYear;
use App\Entity\Student;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture
{
    private $doctrine;
    private $faker;
    private $hasher;
    private $manager;

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $hasher)
    {
        $this->doctrine = $doctrine;
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadTags();
        $this->loadSchoolYears();
        $this->loadProjects();
        $this->loadStudents();
    }

    public function loadProjects(): void
    {
        $datas = [
            [
                'name' => 'Maquettage',
                'description' => null,
                'clientName' => 'Foo Bar',
                'startDate' => DateTime::createFromFormat('d/m/Y', '01/02/2023'),
                'checkPointDate' => DateTime::createFromFormat('d/m/Y', '01/03/2023'),
                'deliveryDate' => DateTime::createFromFormat('d/m/Y', '01/04/2023'),
                // @todo ajouter les tags id 1, id 2 et id 3 de la liste des tags
            ],
            [
                'name' => 'Student',
                'description' => null,
                'clientName' => 'Foo Bar',
                'startDate' => DateTime::createFromFormat('d/m/Y', '01/02/2023'),
                'checkPointDate' => DateTime::createFromFormat('d/m/Y', '01/03/2023'),
                'deliveryDate' => DateTime::createFromFormat('d/m/Y', '01/04/2023'),
                // @todo ajouter les tags id 1, id 2 et id 3 de la liste des tags
            ],
        ];

        foreach ($datas as $data) {
            $project = new Project();
            $project->setName($data['name']);
            $project->setDescription($data['description']);
            $project->setClientName($data['clientName']);
            $project->setStartDate($data['startDate']);
            $project->setCheckPointDate($data['checkPointDate']);
            $project->setDeliveryDate($data['deliveryDate']);

            $this->manager->persist($project);
        }

        // 10 projets avec des données dynamiques
        for ($i = 0; $i < 10; $i++) {
            $project = new Project();
            $project->setName($this->faker->sentence(3));
            $project->setDescription($this->faker->optional($weight = 0.6)->sentence());
            $project->setClientName($this->faker->name());
            $project->setStartDate($this->faker->optional($weight = 0.8)->dateTimeBetween('-2 month', '-1 month'));
            $project->setCheckPointDate($this->faker->optional($weight = 0.2)->dateTimeBetween('-2 week', '-1 week'));
            $project->setDeliveryDate($this->faker->optional($weight = 0.3)->dateTimeBetween('+2 month', '+3 month'));

            // @todo ajouter des tags au projets de façon aléatoire

            $this->manager->persist($project);
        }

        $this->manager->flush();
    }

    public function loadStudents(): void
    {
        // school years
        $repository = $this->manager->getRepository(SchoolYear::class);
        $schoolYears = $repository->findAll();

        // tags
        $repository = $this->manager->getRepository(Tag::class);
        $tags = $repository->findAll();

        // projects
        $repository = $this->manager->getRepository(Project::class);
        $projects = $repository->findAll();

        // données de test statiques
        $datas = [
            [
                // user
                'email' => 'foo.bar@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                // student
                'firstname' => 'Foo',
                'lastname' => 'Bar',
                'createdAt' => DateTimeImmutable::createFromFormat('d/m/Y', '01/01/2022'),
                'schoolYear' => $schoolYears[0],
                'project' => $projects[0],
                'tags' => [$tags[0], $tags[1], $tags[2]],
            ],
            [
                // user
                'email' => 'baz.baz@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                // student
                'firstname' => 'Baz',
                'lastname' => 'Baz',
                'createdAt' => DateTimeImmutable::createFromFormat('d/m/Y', '02/01/2022'),
                'schoolYear' => $schoolYears[0],
                'project' => $projects[0],
                'tags' => [$tags[0], $tags[1], $tags[2]],
            ],
        ];

        foreach ($datas as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $password = $this->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            $user->setRoles($data['roles']);

            $student = new Student();
            $student->setFirstname($data['firstname']);
            $student->setLastname($data['lastname']);
            $student->setCreatedAt($data['createdAt']);
            $student->setUser($user);
            $student->setSchoolYear($data['schoolYear']);
            $student->setProject($data['project']);

            foreach ($data['tags'] as $tag) {
                $student->addTag($tag);
            }

            $this->manager->persist($student);
        }

        // données de test dynamiques
        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email());
            $password = $this->hasher->hashPassword($user, '123');
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

            $student = new Student();
            $student->setFirstname($this->faker->firstname());
            $student->setLastname($this->faker->lastname());
            $student->setCreatedAt(new DateTimeImmutable());
            $student->setUser($user);
            $student->setSchoolYear($this->faker->randomElement($schoolYears));
            $student->setProject($this->faker->randomElement($projects));

            foreach ($this->faker->randomElements($tags) as $tag) {
                $student->addTag($tag);
            }

            $this->manager->persist($student);
        }

        $this->manager->flush();
    }

    public function loadSchoolYears(): void
    {
        // données de test statiques
        $datas = [
            [
                'name' => 'Promo Foo Bar Baz',
                'description' => null,
                'startDate' => DateTime::createFromFormat('Y-m-d', '2022-01-01'),
                'endDate' => DateTime::createFromFormat('Y-m-d', '2022-04-30'),
            ],
            [
                'name' => 'Promo Lorem Ipsum',
                'description' => 'Une promo formidable',
                'startDate' => DateTime::createFromFormat('Y-m-d', '2022-06-01'),
                'endDate' => DateTime::createFromFormat('Y-m-d', '2022-09-30'),
            ],
        ];

        foreach ($datas as $data) {
            // création d'un nouvel objet
            $schoolYear = new SchoolYear();
            // affectation des valeurs statiques
            $schoolYear->setName($data['name']);
            $schoolYear->setDescription($data['description']);
            $schoolYear->setStartDate($data['startDate']);
            $schoolYear->setEndDate($data['endDate']);

            // demande d'enregistrement de l'objet
            $this->manager->persist($schoolYear);
        }

        // données de test dynamiques
        for ($i = 0; $i < 10; $i++) {
            // création d'un nouvel objet
            $schoolYear = new SchoolYear();
            // affectation des valeurs dynamiques
            $schoolYear->setName(ucfirst($this->faker->word()));
            $schoolYear->setDescription($this->faker->sentence());
            $schoolYear->setStartDate($this->faker->dateTimeBetween('-10 week', '-6 week'));
            $schoolYear->setEndDate($this->faker->dateTimeBetween('+8 week', '+12 week'));

            // demande d'enregistrement de l'objet
            $this->manager->persist($schoolYear);
        }

        // exécution des requêtes SQL
        $this->manager->flush();
    }

    public function loadTags(): void
    {
        // données de test statiques
        $datas = [
            [
                'name' => 'HTML',
                'description' => null,
            ],
            [
                'name' => 'CSS',
                'description' => 'Langage de programmation pour styliser',
            ],
            [
                'name' => 'JS',
                'description' => 'Langage de programmation pour rendre dynamique',
            ],
        ];

        foreach ($datas as $data) {
            // création d'un nouvel objet
            $tag = new Tag();
            // affectation des valeurs statiques
            $tag->setName($data['name']);
            $tag->setDescription($data['description']);

            // demande d'enregistrement de l'objet
            $this->manager->persist($tag);
        }

        // données de test dynamiques
        for ($i = 0; $i < 10; $i++) {
            // création d'un nouvel objet
            $tag = new Tag();
            // affectation des valeurs dynamiques
            $tag->setName(ucfirst($this->faker->word()));
            $tag->setDescription($this->faker->sentence());

            // demande d'enregistrement de l'objet
            $this->manager->persist($tag);
        }

        // exécution des requêtes SQL
        $this->manager->flush();
    }
}
