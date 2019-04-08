<?php

namespace App\DataFixtures;

use App\Entity\CrewType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CrewTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $pilot = new CrewType();
        $pilot->setJob("Pilot");
        $pilot->setLevel(1);
        $manager->persist($pilot);
        $this->addReference("crew_1", $pilot);

        $copilot = new CrewType();
        $copilot->setJob("Copilot");
        $copilot->setLevel(2);
        $manager->persist($copilot);
        $this->addReference("crew_2", $copilot);

        $stewart = new CrewType();
        $stewart->setJob("Stewart");
        $stewart->setLevel(3);
        $manager->persist($stewart);
        $this->addReference("crew_3", $stewart);

        $cleaner = new CrewType();
        $cleaner->setJob("Cleaner");
        $cleaner->setLevel(4);
        $manager->persist($cleaner);
        $this->addReference("crew_4", $cleaner);

        $manager->flush();
    }
}
