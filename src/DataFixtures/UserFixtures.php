<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    // Password for all users is : password

    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        /** @var Country $france */
        $france = $manager->getRepository(Country::class)->findOneBy(['name' => 'France']);
        /** @var Country $belgium */
        $belgium = $manager->getRepository(Country::class)->findOneBy(['name' => 'Belgique']);

        // Creating admin
        $admin = new User();
        $admin->setUsername("admin");
        $admin->setBirthday($faker->dateTimeBetween());
        $admin->setCity($faker->city);
        $admin->setEmail($faker->email);
        $admin->setDescription($faker->text);
        $admin->setCountry($france);
        $password = $this->userPasswordEncoder->encodePassword($admin, "admin");
        $admin->setPassword($password);
        $admin->setDepartment($this->getReference('fr_department_' . 60));
        $admin->setEnabled(true);
        $admin->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
        $admin->eraseCredentials();
        $manager->persist($admin);

        // Creating users
        for ($i = 0; $i < 40; $i++) {
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setBirthday($faker->dateTimeBetween());
            $user->setCity($faker->city);
            $user->setEmail($faker->email);
            $user->setDescription($faker->text);
            if(rand(0,10) < 3) {
                $be = ["VAN", "VLI", "VOV", "WHT", "WLG"];
                $user->setDepartment($this->getReference('be_department_' . $be(array_rand($be))));
                $user->setCountry($belgium);
            }
            else{
                $user->setDepartment($this->getReference('fr_department_' . rand(30, 60)));
                $user->setCountry($france);
            }
            $password = $this->userPasswordEncoder->encodePassword($user, "password");
            $user->setPassword($password);
            $user->setEnabled(true);
            $user->setRoles(["ROLE_USER"]);
            $user->eraseCredentials();
            $this->addReference("user_" . $i, $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    // Load order : 3
    public function getDependencies()
    {
        return [
            CountryFixtures::class,
            DepartmentFixtures::class
        ];
    }
}
