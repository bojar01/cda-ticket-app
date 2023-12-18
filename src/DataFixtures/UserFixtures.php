<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $passwordHasher;

    public const USER_REFERENCE = 'user_test';

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        for($index = 0; $index < 5; $index++){

            $user = new User();

            $user->setEmail('test'.$index.'@test.fr');
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'testtest')
            );
            $user->setFirstname('test'.$index);
            $user->setLastname('test'.$index);

            $user->setSession($this->getReference(SessionFixtures::SESSION_REFERENCE));

            $user->setRoles(self::roleAttribution($index));

            $user->setAuthorized(true);

            if($index == 0) {
                $this->addReference(self::USER_REFERENCE, $user);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            SessionFixtures::class
        );
    }

    public function roleAttribution($index) {
        switch($index) {
            case 0:
                return ["ROLE_STUDENT", "ROLE_COACH", "ROLE_ADMIN"];
                break;
            case 1:
                return ["ROLE_STUDENT", "ROLE_COACH"];
                break;
            default:
                return ["ROLE_STUDENT"];
        }
    }
}
