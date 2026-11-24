<?php

namespace App\DataFixtures;

use App\Entity\Keyword;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class KeywordFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $heroes = [
            'Digital Art',
            'Traditional Art',
            'Sculptures',
            'Fursuit Supplies'
        ];

        foreach ($heroes as $hero) {
            $keyword = new Keyword()
                ->setIsHero(true)
                ->setIsAfterDark(false)
                ->setName($hero)
            ;
            $manager->persist($keyword);
        }

        $manager->flush();
    }
}
