<?php

namespace App\DataFixtures;

use App\Entity\ItemToBorrow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use PhpParser\Node\Stmt\Const_;

class ItemToBorrowFixtures extends Fixture implements DependentFixtureInterface
{
    public const DATA = 
        [
            [
                'name' => 'Perceuse',
                'category' => 'Bricolage',
                'description' => 'Cette perceuse permet de faire des trous de toutes tailles dans des murs (hors brique).',
                'picture' => 'perceuse.jpg',
            ],
            [
                'name' => 'Poussette',
                'category' => 'Puériculture',
                'description' => 'Cette poussette est équipée d\'un cosy et d\'une nacelle pour les nourrissons. Possibilité de fournir la protection de pluie également.',
                'picture' => 'poussette.jpg',
            ],
            [
                'name' => 'Kit de peinture',
                'category' => 'Bricolage',
                'description' => 'Je vous fournis tout le nécessaire pour repeindre une pièce chez vous : 3 pinceaux de plusieurs tailles, un rouleau et son contenant et des bâches.',
                'picture' => 'peinture.jpg',
            ],
            [
                'name' => 'Escabeau',
                'category' => 'Bricolage',
                'description' => 'Escabeau 10 marches, permet d\atteindre un plafond de taille standard.',
                'picture' => 'escabeau.jpg',
            ],
            [
                'name' => 'Hamac',
                'category' => 'Divers',
                'description' => 'Je ne m\'en sers plus.',
                'picture' => 'hamac.jpg',
            ],
            [
                'name' => 'Ventilateur',
                'category' => 'Divers',
                'description' => 'Petit ventilateur d\'appoint, idéal pour le bureau, 3 vitesses.',
                'picture' => 'ventilateur.jpg',
            ],
            [
                'name' => 'Parasol',
                'category' => 'Jardin',
                'description' => 'Je prête ce parasol, je l\utilise peu depuis que j\ai installé un auvent sur ma terrasse (j\en ai besoin uniquement quand je reçois beaucoup de monde). Il se fixe au milieu d\une table ou avec un pied lesté (prévoir d\être 2 pour porter).',
                'picture' => 'parasol.jpg',
            ],
            [
                'name' => 'Chaises de jardin',
                'category' => 'Jardin',
                'description' => 'j\'en ai 6 comme ça, que je peux prêter dès que je ne m\'en sers pas.',
                'picture' => 'chaises_jardin.jpg',
            ],
            [
                'name' => 'Tronçonneuse',
                'category' => 'Jardin',
                'description' => 'Tronçonneuse de la marque \'beau jardin\', assez maniable et façile à utiliser. En parfait état de marche.',
                'picture' => 'tronconneuse.jpg',
            ],
            [
                'name' => 'Fer à souder',
                'category' => 'Bricolage',
                'description' => 'Hyper pratique pour les petites réparations d\électronique.',
                'picture' => 'fer_a_souder.jpg',
            ],
            [
                'name' => 'Scie circulaire',
                'category' => 'Bricolage',
                'description' => 'Scie circulaire achetée l\année dernière, facile à utiliser et sécurisée.',
                'picture' => 'scie_circulaire.jpg',
            ],
            [
                'name' => 'Scie sauteuse',
                'category' => 'Bricolage',
                'description' => 'Standard.',
                'picture' => 'scie_sauteuse.jpg',
            ],
            [
                'name' => 'Scie à métaux',
                'category' => 'Bricolage',
                'description' => 'Hyper pratique, ça demande un peu d\entraînement mais on peut faire des trucs sympas avec',
                'picture' => 'scie_metaux.jpg',
            ],
        ];

    public function load(ObjectManager $manager): void {
        $faker = Factory::create('fr_FR');
        foreach (self::DATA as $key => $item) {
            $itemToBorrow = new ItemToBorrow();
            $itemToBorrow->setName($item['name']);
            $itemToBorrow->setCategory($item['category']);
            $itemToBorrow->setDescription($item['description']);
            $itemToBorrow->setPicture($item['picture']);
            $itemToBorrow->setUserWhoOffer($this->getReference('user_' . $faker->numberBetween(0,4)));
            $this->addReference('itemToBorrow_' . $key, $itemToBorrow);
            $manager->persist($itemToBorrow);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}

