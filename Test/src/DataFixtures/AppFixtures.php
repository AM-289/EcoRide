<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    )
    {

    }
    
    public function load(ObjectManager $manager): void
    {
        $user = (new User());
        $user->setRoles(['ROLE_ADMIN'])
            ->setEmail('admin@doe.fr')
            ->setUsername('admin')
            ->setIsVerified('true')
            ->setPassword($this->hasher->hashPassword($user, 'admin'))
            ->setApiToken('admin_token');
        $manager->persist($user);

        $faker = Factory::create('fr_FR');
       


        for ($i = 1; $i <= 10; $i++) {
            $user = (new User());
            $username = $faker->name();
            $user->setRoles([])
                ->setUsername($username)
                ->setEmail("user{$i}@doe.fr")
                ->setIsVerified('true')
                ->setPassword($this->hasher->hashPassword($user, '0000'))
                ->setApiToken("user{$i}");
            $manager->persist($user);
        }
        
        $manager->flush();
    }
}
