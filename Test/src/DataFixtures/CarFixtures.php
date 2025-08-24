<?php

namespace App\DataFixtures;

use App\Entity\Car;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Provider\FakeCar;

class CarFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new FakeCar($faker));

        for ($i = 1; $i <= 10; $i++) {
            $car = (new Car())
                ->setBrand($faker->vehicleBrand())
                ->setModel($faker->vehiculeModel())
                ->setLiscencePlate($faker->vehicleRegistration('[A-Z]{2}-[0-9]{3}-[A-Z]{2}'))
                ->setAnimal((bool)random_int(0, 1))
                ->setSmoke((bool)random_int(0, 1))
                ->setEnergy($faker->vehicleFuelType());
            $manager->persist($car);
        }

        $manager->flush();
    }
}
