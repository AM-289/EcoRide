<?php

namespace App\DataFixtures;

use App\Entity\Car;
use App\DataFixtures\AppFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\FakeCar;

class CarFixtures extends Fixture implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new FakeCar($faker));

        for ($i = 1; $i <= 10; $i++) 
        //foreach ($users as $user): if ($user['role'] == 'ROLE_DRIVER'):
        //$cnt = count($driver);
        //for($i = 0;$i < $cnt,$user[$i]['section'] == 'headcontent' ;$i++)
        {
            $car = (new Car())
                ->setBrand($faker->vehicleBrand())
                ->setModel($faker->vehicleModel())
                ->setLiscencePlate($faker->vehicleRegistration('[A-Z]{2}-[0-9]{3}-[A-Z]{2}'))
                ->setAnimal((bool)random_int(0, 1))
                ->setSmoke((bool)random_int(0, 1))
                ->setEnergy($faker->vehicleFuelType())
                ->setDriver($this->getReference('DRIVER'.$faker->numberBetween(11, 20), User::class));
            $manager->persist($car);
        }

        $manager->flush();
    }

    //to load the user(driver) faker before the car one
    public function getDependency() {
        return [AppFixtures::class];
    }
}
