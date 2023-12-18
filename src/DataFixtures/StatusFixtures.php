<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatusFixtures extends Fixture
{
    public const STATUS_REFERENCES = 'status_test';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $status = new Status();
        $status->setName('En attente');
        $this->addReference(self::STATUS_REFERENCES, $status);

        $manager->persist($status);

        $status1 = new Status();
        $status1->setName('En cours');
        $manager->persist($status1);

        $status2 = new Status();
        $status2->setName('Finalisé');
        $manager->persist($status2);


        $manager->flush();
    }
}
