<?php

namespace App\DataFixtures;

use App\Entity\ControlTower;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class ControlTowerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();

        for ($i=0; $i <= 40; $i++) {
            $controlTower = new ControlTower();
            $controlTower->setName($faker->name);
            $controlTower->setAirport($this->getReference("airport_" . rand(0, 14)));
            $manager->persist($controlTower);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            AirportFixtures::class,
        );
    }
}
