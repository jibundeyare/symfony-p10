<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $doctrine;
    private $faker;
    private $hasher;

    public static function getGroups(): array
    {
        return ['prod'];
    }

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $hasher)
    {
        $this->doctrine = $doctrine;
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // @todo ajouter la création d'un compte admin (c-à-d un user non rattaché à un student)
        // @info les admin possède le rôle ROLE_ADMIN

        $manager->flush();
    }
}
