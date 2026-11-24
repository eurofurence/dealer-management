<?php

namespace App\DataFixtures;

use App\Entity\TablePackage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TablePackageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $ddPackages = [
            ['half', 'Half Table', 5000],
            ['full', 'Full Table', 10000],
            ['plus', 'Plus Table', 15000],
            ['double', 'Double Table', 20000],
        ];

        foreach ($ddPackages as $package) {
            $tablePackage = new TablePackage()
                ->setProductKey($package[0])
                ->setName($package[1])
                ->setPrice($package[2])
                ->setRequestableForDealership(true)
            ;
            $manager->persist($tablePackage);
        }

        $manager->flush();
    }
}
