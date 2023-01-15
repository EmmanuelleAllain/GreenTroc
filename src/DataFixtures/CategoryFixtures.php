<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\ItemToBorrow;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORY = [
        'Bricolage',
        'Divers',
        'Jardin',
        'Puériculture'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach(self::CATEGORY as $categoryName) {
            $category = new Category();
            $category->setCategoryName($categoryName);
            $this->addReference($categoryName, $category);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
