<?php

namespace App\DataFixtures;

use App\Entity\Passenger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class PassengerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        for ($i=0; $i <= 40; $i++) {

            $passenger = new Passenger();
            $passenger->setFirstname($faker->firstName);
            $passenger->setLastname($faker->lastName);
            $passenger->setSex($faker->boolean);
            $passenger->setCountry($this->getReference("country_" . rand(1, 50)));
            $passenger->setFlight($this->getReference("flight_" . rand(0, 60)));
            $manager->persist($passenger);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            FlightFixtures::class,
            CountryFixtures::class
        );
    }
}
