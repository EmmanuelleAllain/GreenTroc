<?php

namespace App\DataFixtures;

use App\Entity\ItemToBorrow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ItemToBorrowFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
    $faker = Factory::create('fr_FR');
            for ($j = 0; $j < 12; $j++) {
            $itemToBorrow = new ItemToBorrow();
            $itemToBorrow->setName($faker->unique()->randomElement([
                'Perçeuse',
                'Kit de peinture (pinceaux, rouleaux et bâches',
                'Siège auto 15 mois à 4 ans',
                'Poussette',
                'Gaufrier',
                'Escabeau',
                'Ponçeuse',
                'Appareil à raclette',
                'Machine à coudre',
                'Friteuse',
                'Scie sauteuse',
                'Décolleuse de papier peint'
            ]));
            $itemToBorrow->setCategory($faker->randomElement([
                'Bricolage', 'Puériculture', 'Cuisine', 'Divers'
            ]));
            $itemToBorrow->setDescription($faker->paragraph());
            $itemToBorrow->setPicture($faker->randomElement([
                'poussette.jpg', 'escabeau.jpg', 'perceuse.jpg', 'peinture.jpg'
            ]));
            $itemToBorrow->setUserWhoOffer($this->getReference('user_' . $faker->numberBetween(0,4)));
            $this->addReference('itemToBorrow_' . $j, $itemToBorrow);
            $manager->persist($itemToBorrow);
        $manager->flush();
        }
    }
    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
