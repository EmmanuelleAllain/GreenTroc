<?php

namespace App\DataFixtures;

use App\Entity\Borrow;
use App\Entity\ItemToBorrow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class BorrowFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
                for ($k = 0; $k < 12; $k++) {
                    for ($j = 0; $j < 5; $j++) {
                        $borrow = new Borrow();
                        $borrow->setDate($faker->dateTimeBetween('-1 week', '+4 week'));
                        $borrow->setBorrowedItem($this->getReference('itemToBorrow_' . $k));
                        $borrow->setUserWhoBorrow($this->getReference('user_' . $j));
                        $borrow->setStatus($faker->randomElement([
                            'en attente', 'validé', 'annulé'
                        ]));
                        $manager->persist($borrow);
                        $manager->flush();
                    }
                }
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ItemToBorrowFixtures::class
        ];
    }
}
