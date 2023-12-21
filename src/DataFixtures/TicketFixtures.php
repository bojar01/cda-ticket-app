<?php

namespace App\DataFixtures;

use App\Entity\Ticket;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TicketFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for($index = 0; $index < 10; $index++){
            $ticket = new Ticket();

            $ticket->setSubject('J\'ai un probleme avec mon code');
            $ticket->setPriority(true);

            $ticket->setTechnology($this->getReference(TechnologyFixtures::TECHNOLOGY_REFERENCE));

            $ticket->setStatus($this->getReference(StatusFixtures::STATUS_REFERENCES));

            $ticket->setOwner($this->getReference(UserFixtures::USER_REFERENCE));
            // $ticket->setAngel($this->getReference(UserFixtures::USER_REFERENCE));
            $ticket->setImage('default.jpg');

            $manager->persist($ticket);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TechnologyFixtures::class,
            StatusFixtures::class,
            UserFixtures::class
        );
    }
}
