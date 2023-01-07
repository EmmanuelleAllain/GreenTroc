<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $admin = new User();
        $admin->setFirstname('admin');
        $admin->setLastName('admin');
        $admin->setEmail('admin@admin.fr');
        $hashedPassword = $this->passwordHasher->hashPassword(
                $admin,
                'admin'
            );
        $admin->setPassword($hashedPassword);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPostcode(69003);
        $admin->setCity('Lyon');
        $admin->setStatus(true);
        $manager->persist($admin);

        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastName($faker->lastName());
            $user->setEmail($faker->email());
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'password'
            );
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);
            $user->setAddress($faker->randomElement([
                '12 rue Francis Pressensé', '20 rue Antoine Charial', '12 rue Delandine', '400 rue André Philip', '20 avenue des Brotteaux', '14 boulevard de l\'opéra'
            ]));
            $user->setPostcode($faker->randomElement([69003, 69006, 69100]));
            $user->setCity($faker->randomElement(['Lyon', 'Villeurbanne']));
            $user->setStatus(true);
            $this->addReference('user_' . $i, $user);

            $manager->persist($user);
        }
        $manager->flush();
    }
}
