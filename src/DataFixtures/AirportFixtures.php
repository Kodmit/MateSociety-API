<?php

namespace App\DataFixtures;

use App\Entity\Airport;
use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;


class AirportFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();

        for ($i=0; $i < 15; $i++) {
            $airport = new Airport();
            $airport->setName($faker->name);
            $airport->setCountry($this->getReference("country_" . rand(1, 50)));
            $airport->setCity($faker->city);
            $manager->persist($airport);
            $this->addReference("airport_" . $i, $airport);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CountryFixtures::class,
        );
    }
}
