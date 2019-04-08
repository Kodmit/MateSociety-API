<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();

        for ($i=0; $i <= 10; $i++) {

            $company = new Company();
            $company->setCountry($this->getReference("country_" . rand(1, 50)));
            $company->setName($faker->company);
            $company->setReference($faker->randomAscii);
            $manager->persist($company);
            $this->addReference("company_" . $i, $company);
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
