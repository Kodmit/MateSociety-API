<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CountryFixtures extends Fixture
{

    // Load order : 1

    public function load(ObjectManager $manager)
    {
        $countries = ["France", "Belgique", "Suisse"];
        $a = 1;
        foreach ($countries as $country){
            $orm = new Country();
            $orm->setName($country);
            $manager->persist($orm);
            $this->addReference("country_" . $a, $orm);
            $a++;
        }

        $manager->flush();

    }
}
