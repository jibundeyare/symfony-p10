<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\SchoolYear;
use App\Entity\Tag;
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
    }

    public function loadSchoolYears()
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
