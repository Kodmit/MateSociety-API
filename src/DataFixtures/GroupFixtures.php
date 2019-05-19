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

        for ($i = 0; $i < 20; $i++) {
            $group = new Group();
            $group->setCountry($this->getReference('country_' . rand(1, 3)));
            $group->setCreator($this->getReference('user_' . $i));
            if(rand(0,10) > 4){
                $group->setDepartment($this->getReference('fr_department_FR-' . rand(30, 60)));
            }
            else{
                $be = ["VAN", "VLI", "VOV", "WHT", "WLG"];
                $group->setDepartment($this->getReference('be_department_BE-' . $be[array_rand($be)]));
            }
            $group->addUser($this->getReference('user_' . $i));
            $rand_j = rand(22, 30);
            for ($j = 21; $j < $rand_j; $j++) {
                $group->addUser($this->getReference('user_' . $j));
            }
            $group->setCity($faker->city);
            $group->setDescription($faker->text);
            $group->setName($faker->company);

            $manager->persist($group);
        }

        $manager->flush();
    }


    // Load order : 4
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }

}