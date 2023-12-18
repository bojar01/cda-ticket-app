<?php

namespace App\DataFixtures;

use App\Entity\Technology;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TechnologyFixtures extends Fixture
{
    public const TECHNOLOGY_REFERENCE = 'technology_test';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $technology = new Technology();

        $technology->setName('Symfony');
        $this->AddReference(self::TECHNOLOGY_REFERENCE, $technology);
        $manager->persist($technology);

        $technology2 = new Technology();
        $technology2->setName('Laravel');
        $manager->persist($technology2);

        $technology3 = new Technology();
        $technology3->setName('React');
        $manager->persist($technology3);

        $manager->flush();
    }
}
