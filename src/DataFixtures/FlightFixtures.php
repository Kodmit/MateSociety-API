<?php

namespace App\DataFixtures;

use App\Entity\Airport;
use App\Entity\Company;
use App\Entity\Flight;
use App\Entity\Plane;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class FlightFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();

        for ($i=0; $i <= 60; $i++) {

            $flight = new Flight();
            $flight->setArrival($faker->address);
            $flight->setDeparture($faker->address);
            $flight->setArrivalTime($faker->dateTime);
            $flight->setDepartureTime($faker->dateTime);
            $flight->setCompany($this->getReference("company_" . rand(1, 10)));
            $flight->setAirport($this->getReference("airport_" . rand(1, 14)));
            $flight->setPlane($this->getReference("plane_" . rand(1, 50)));
            $this->addReference("flight_" . $i, $flight);
            $manager->persist($flight);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CompanyFixtures::class,
            AirportFixtures::class,
            PlaneFixtures::class
        );
    }
}
