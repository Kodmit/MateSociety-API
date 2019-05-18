<?php

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class GroupFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 15; $i++) {
            $group = new Group();
            $group->setCountry($this->getReference('country_' . rand(1, 3)));
            $group->setCreator($this->getReference('user_' . $i));
            for ($j = 0; $j < rand(1, 10); $j++) {
                $group->addUser($this->getReference('user_' . $j));
            }
            $group->setCity($faker->city);
            $group->setDescription($faker->text);
            $group->setName($faker->company);

            $manager->persist($group);
        }

        $manager->flush();
    }


    // Load order : 3
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }

}