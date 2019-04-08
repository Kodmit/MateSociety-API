<?php

namespace App\DataFixtures;

use App\Entity\Airport;
use App\Entity\Flight;
use App\Entity\Gate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class GateFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        for ($i=0; $i <= 30; $i++) {

            $gate = new Gate();
            $gate->setStatus($faker->boolean);
            $gate->setAirport($this->getReference("airport_" . rand(1, 14)));
            $gate->setFlight($this->getReference("flight_" . rand(0, 60)));
            $manager->persist($gate);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            AirportFixtures::class,
            FlightFixtures::class
        );
    }
}
