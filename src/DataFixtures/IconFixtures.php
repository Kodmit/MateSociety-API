<?php

namespace App\DataFixtures;

use App\Entity\Icon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class IconFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $icons = [
            'Boxe' => 'boxing-glove',
            'Cinéma' => 'film-alt',
            'Football' => 'futbol',
            'Rugby' => 'football-ball',
            'Basket' => 'basketball-ball',
            'Hockey' => 'hockey-sticks',
            'Tennis' => 'racquet',
            'Golf' => 'golf-club',
            'Bowling' => 'bowling-ball',
            'Musculation' => 'dumbbell',
            'Ski' => 'skiing',
            'Badminton' => 'shuttlecock',
            'book' => 'book-open',
            'Course' => 'running',
            'Baseball' => 'baseball',
            'Randonné' => 'hiking',
            'Camping' => 'campground',
            'Survivalisme' => 'campfire',
            'Informatique' => 'code',
            'Vélo' => 'bicycle',
            'Natation' => 'swimmer',
            'backpack' => 'backpack',
            'Marche' => 'walking',
            'Jeux' => 'dice',
            'Voyages' => 'plane',
            'Musique' => 'guitar',
            'Tir à l\'arc' => 'bullseye-arrow',
            'Tir' => 'crosshairs',
            'Moto' => 'motorcycle',
            'Ping Pong' => 'table-tennis',
            'sunglasses' => 'sunglasses',
            'sword' => 'sword',
            'Autre' => 'chevron-circle-down',
        ];

        foreach ($icons as $key => $value){
            $icon = new Icon();
            $icon->setName($key);
            $icon->setPath($value);
            $manager->persist($icon);
        }
        $manager->flush();
    }
}