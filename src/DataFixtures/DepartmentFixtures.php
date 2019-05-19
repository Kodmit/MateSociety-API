<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DepartmentFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $franceDepts = [
            'FR-01' => 'Ain',
            'FR-02' => 'Aisne',
            'FR-03' => 'Allier',
            'FR-04' => 'Alpes-de-Haute-Provence',
            'FR-05' => 'Hautes-Alpes',
            'FR-06' => 'Alpes-Maritimes',
            'FR-07' => 'Ardèche',
            'FR-08' => 'Ardennes',
            'FR-09' => 'Ariège',
            'FR-10' => 'Aube',
            'FR-11' => 'Aude',
            'FR-12' => 'Aveyron',
            'FR-13' => 'Bouches-du-Rh&#244;ne',
            'FR-14' => 'Calvados',
            'FR-15' => 'Cantal',
            'FR-16' => 'Charente',
            'FR-17' => 'Charente-Maritime',
            'FR-18' => 'Cher',
            'FR-19' => 'Corrèze',
            'FR-21' => 'Côte-d\'Or',
            'FR-22' => 'Côtes d\'Armor',
            'FR-23' => 'Creuse',
            'FR-24' => 'Dordogne',
            'FR-25' => 'Doubs',
            'FR-26' => 'Drôme',
            'FR-27' => 'Eure',
            'FR-28' => 'Eure-et-Loir',
            'FR-29' => 'Finistère',
            'FR-2A' => 'Corse-du-Sud',
            'FR-2B' => 'Haute-Corse',
            'FR-30' => 'Gard',
            'FR-31' => 'Haute-Garonne',
            'FR-32' => 'Gers',
            'FR-33' => 'Gironde',
            'FR-34' => 'Hérault',
            'FR-35' => 'Ille-et-Vilaine',
            'FR-36' => 'Indre',
            'FR-37' => 'Indre-et-Loire',
            'FR-38' => 'Isère',
            'FR-39' => 'Jura',
            'FR-40' => 'Landes',
            'FR-41' => 'Loir-et-Cher',
            'FR-42' => 'Loire',
            'FR-43' => 'Haute-Loire',
            'FR-44' => 'Loire-Atlantique',
            'FR-45' => 'Loiret',
            'FR-46' => 'Lot',
            'FR-47' => 'Lot-et-Garonne',
            'FR-48' => 'Lozère',
            'FR-49' => 'Maine-et-Loire',
            'FR-50' => 'Manche',
            'FR-51' => 'Marne',
            'FR-52' => 'Haute-Marne',
            'FR-53' => 'Mayenne',
            'FR-54' => 'Meurthe-et-Moselle',
            'FR-55' => 'Meuse',
            'FR-56' => 'Morbihan',
            'FR-57' => 'Moselle',
            'FR-58' => 'Nièvre',
            'FR-59' => 'Nord',
            'FR-60' => 'Oise',
            'FR-61' => 'Orne',
            'FR-62' => 'Pas-de-Calais',
            'FR-63' => 'Puy-de-Dôme',
            'FR-64' => 'Pyrénées-Atlantiques',
            'FR-65' => 'Hautes-Pyrénées',
            'FR-66' => 'Pyrénées-Orientales',
            'FR-67' => 'Bas-Rhin',
            'FR-68' => 'Haut-Rhin',
            'FR-69' => 'Rhône',
            'FR-70' => 'Haute-Saône',
            'FR-71' => 'Saône-et-Loire',
            'FR-72' => 'Sarthe',
            'FR-73' => 'Savoie',
            'FR-74' => 'Haute-Savoie',
            'FR-75' => 'Paris',
            'FR-76' => 'Seine-Maritime',
            'FR-77' => 'Seine-et-Marne',
            'FR-78' => 'Yvelines',
            'FR-79' => 'Deux-Sèvres',
            'FR-80' => 'Somme',
            'FR-81' => 'Tarn',
            'FR-82' => 'Tarn-et-Garonne',
            'FR-83' => 'Var',
            'FR-84' => 'Vaucluse',
            'FR-85' => 'Vendée',
            'FR-86' => 'Vienne',
            'FR-87' => 'Haute-Vienne',
            'FR-88' => 'Vosges',
            'FR-89' => 'Yonne',
            'FR-90' => 'Territoire de Belfort',
            'FR-91' => 'Essonne',
            'FR-92' => 'Hauts-de-Seine',
            'FR-93' => 'Seine-St-Denis',
            'FR-94' => 'Val-de-Marne',
            'FR-95' => 'Val-D\'Oise'
        ];

        foreach ($franceDepts as $key => $value){
            $franceDept = new Department();
            $franceDept->setCode($key);
            $franceDept->setName($value);
            $franceDept->setCountry($this->getReference('country_1'));

            $manager->persist($franceDept);
            $this->addReference("fr_department_" . $key, $franceDept);
        }

        $manager->flush();

        $belgiumDepts = [
          'BE-VAN' => 'Anvers',
          'BE-VLI' => 'Limbourg',
          'BE-VOV' => 'Flandre-Orientale',
          'BE-VBR' => 'Brabant flamand',
          'BE-VWV' => 'Flandre-Occidentale',
          'BE-WBR' => 'Brabant wallon',
          'BE-WHT' => 'Hainaut',
          'BE-WLG' => 'Liège',
          'BE-WLX' => 'Luxembourg',
          'BE-WNA' => 'Namur'
        ];

        foreach ($belgiumDepts as $key => $value){
            $belgiumDept = new Department();
            $belgiumDept->setCode($key);
            $belgiumDept->setName($value);
            $belgiumDept->setCountry($this->getReference('country_2'));

            $manager->persist($belgiumDept);
            $this->addReference("be_department_" . $key, $belgiumDept);
        }

        $manager->flush();

        $switzerlandDepts = [
            'CH-ZH' => 'Zurich',
            'CH-BE' => 'Berne',
            'CH-LU' => 'Lucerne',
            'CH-UR' => 'Uri',
            'CH-SZ' => 'Schwytz',
            'CH-OW' => 'Obwald',
            'CH-NW' => 'Nidwald',
            'CH-GL' => 'Glaris',
            'CH-ZG' => 'Zoug',
            'CH-FR' => 'Fribourg',
            'CH-SO' => 'Soleure',
            'CH-BS' => 'Bâle-Ville',
            'CH-BL' => 'Bâle-Campagne',
            'CH-SH' => 'Schaffhouse',
            'CH-AR' => 'Appenzell Rhodes-Extérieures',
            'CH-AI' => 'Appenzell Rhodes-Intérieures',
            'CH-SG' => 'Saint-Gall',
            'CH-GR' => 'Grisons',
            'CH-AG' => 'Argovie',
            'CH-TG' => 'Thurgovie',
            'CH-TI' => 'Tessin',
            'CH-VD' => 'Vaud',
            'CH-VS' => 'Valais',
            'CH-NE' => 'Neuchâtel',
            'CH-GE' => 'Genève',
            'CH-JU' => 'Jura'
        ];

        foreach ($switzerlandDepts as $key => $value){
            $switzerlandDept = new Department();
            $switzerlandDept->setCode($key);
            $switzerlandDept->setName($value);
            $switzerlandDept->setCountry($this->getReference('country_3'));

            $manager->persist($switzerlandDept);
            $this->addReference("sw_department_" . $key, $switzerlandDept);
        }

        $manager->flush();

    }

    // Load order : 2
    public function getDependencies()
    {
        return [
            CountryFixtures::class
        ];
    }
}
