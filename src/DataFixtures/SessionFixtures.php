<?php

namespace App\DataFixtures;

use App\Entity\Session;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SessionFixtures extends Fixture
{
    public const SESSION_REFERENCE = 'session_test';

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $session = new Session();
        $session->setName('cda-tc-1123');

        $this->addReference(self::SESSION_REFERENCE, $session);

        $manager->persist($session);

        $session1 = new Session();
        $session1->setName('dev-web');

        $manager->persist($session1);

        $manager->flush();
    }
}
