<?php

namespace App\DataFixtures;

use App\Entity\Plane;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class PlaneFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();

        $a = 0;

        for ($i=0; $i <= 50; $i++) {

            $a++;

            $plane1 = new Plane();
            $plane1->setModel("Boeing 747");
            $plane1->setReference($faker->randomAscii);
            $plane1->setAirport($this->getReference("airport_" . rand(1, 14)));
            $plane1->setCompany($this->getReference("company_" . rand(1, 10)));
            $this->addReference("plane_" . $a, $plane1);
            $manager->persist($plane1);

            $a++;

            $plane2 = new Plane();
            $plane2->setModel("Airbus A320");
            $plane2->setCompany($this->getReference("company_" . rand(1, 10)));
            $plane2->setAirport($this->getReference("airport_" . rand(1, 14)));
            $plane2->setReference($faker->randomAscii);
            $this->addReference("plane_" . $a, $plane2);
            $manager->persist($plane2);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            AirportFixtures::class,
            CompanyFixtures::class
        );
    }
}
