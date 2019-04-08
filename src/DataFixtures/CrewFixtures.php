<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Crew;
use App\Entity\CrewType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class CrewFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();

        for ($i=0; $i <= 40; $i++) {
            $company = $manager->getRepository(Company::class)->find(rand(1, 10));
            $crewType = $manager->getRepository(CrewType::class)->find(rand(1, 4));

            $crew = new Crew();
            $crew->setLastname($faker->lastName);
            $crew->setFirstname($faker->firstName);
            $crew->setCompany($this->getReference("company_" . rand(0, 10)));
            $crew->setCrewType($this->getReference("crew_" . rand(1, 4)));
            $manager->persist($crew);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CompanyFixtures::class,
            CrewTypeFixtures::class
        );
    }
}
