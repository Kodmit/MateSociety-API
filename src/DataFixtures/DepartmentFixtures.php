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
            '01' => 'Ain',
            '02' => 'Aisne',
            '03' => 'Allier',
            '04' => 'Alpes-de-Haute-Provence',
            '05' => 'Hautes-Alpes',
            '06' => 'Alpes-Maritimes',
            '07' => 'Ard&#232;che',
            '08' => 'Ardennes',
            '09' => 'Ari&#232;ge',
            '10' => 'Aube',
            '11' => 'Aude',
            '12' => 'Aveyron',
            '13' => 'Bouches-du-Rh&#244;ne',
            '14' => 'Calvados',
            '15' => 'Cantal',
            '16' => 'Charente',
            '17' => 'Charente-Maritime',
            '18' => 'Cher',
            '19' => 'Corr&#232;ze',
            '21' => 'C&#244;te-d\'Or',
            '22' => 'C&#244;tes d\'Armor',
            '23' => 'Creuse',
            '24' => 'Dordogne',
            '25' => 'Doubs',
            '26' => 'Dr&#244;me',
            '27' => 'Eure',
            '28' => 'Eure-et-Loir',
            '29' => 'Finist&#232;re',
            '2A' => 'Corse-du-Sud',
            '2B' => 'Haute-Corse',
            '30' => 'Gard',
            '31' => 'Haute-Garonne',
            '32' => 'Gers',
            '33' => 'Gironde',
            '34' => 'H&#233;rault',
            '35' => 'Ille-et-Vilaine',
            '36' => 'Indre',
            '37' => 'Indre-et-Loire',
            '38' => 'Is&#232;re',
            '39' => 'Jura',
            '40' => 'Landes',
            '41' => 'Loir-et-Cher',
            '42' => 'Loire',
            '43' => 'Haute-Loire',
            '44' => 'Loire-Atlantique',
            '45' => 'Loiret',
            '46' => 'Lot',
            '47' => 'Lot-et-Garonne',
            '48' => 'Loz&#232;re',
            '49' => 'Maine-et-Loire',
            '50' => 'Manche',
            '51' => 'Marne',
            '52' => 'Haute-Marne',
            '53' => 'Mayenne',
            '54' => 'Meurthe-et-Moselle',
            '55' => 'Meuse',
            '56' => 'Morbihan',
            '57' => 'Moselle',
            '58' => 'Ni&#232;vre',
            '59' => 'Nord',
            '60' => 'Oise',
            '61' => 'Orne',
            '62' => 'Pas-de-Calais',
            '63' => 'Puy-de-D&#244;me',
            '64' => 'Pyr&#233;n&#233;es-Atlantiques',
            '65' => 'Hautes-Pyr&#233;n&#233;es',
            '66' => 'Pyr&#233;n&#233;es-Orientales',
            '67' => 'Bas-Rhin',
            '68' => 'Haut-Rhin',
            '69' => 'Rh&#244;ne',
            '70' => 'Haute-Sa&#244;ne',
            '71' => 'Sa&#244;ne-et-Loire',
            '72' => 'Sarthe',
            '73' => 'Savoie',
            '74' => 'Haute-Savoie',
            '75' => 'Paris',
            '76' => 'Seine-Maritime',
            '77' => 'Seine-et-Marne',
            '78' => 'Yvelines',
            '79' => 'Deux-S&#232;vres',
            '80' => 'Somme',
            '81' => 'Tarn',
            '82' => 'Tarn-et-Garonne',
            '83' => 'Var',
            '84' => 'Vaucluse',
            '85' => 'Vend&#233;e',
            '86' => 'Vienne',
            '87' => 'Haute-Vienne',
            '88' => 'Vosges',
            '89' => 'Yonne',
            '90' => 'Territoire de Belfort',
            '91' => 'Essonne',
            '92' => 'Hauts-de-Seine',
            '93' => 'Seine-St-Denis',
            '94' => 'Val-de-Marne',
            '95' => 'Val-D\'Oise'
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
          'VAN' => 'Anvers',
          'VLI' => 'Limbourg',
          'VOV' => 'Flandre-Orientale',
          'VBR' => 'Brabant flamand',
          'VWV' => 'Flandre-Occidentale',
          'WBR' => 'Brabant wallon',
          'WHT' => 'Hainaut',
          'WLG' => 'Liège',
          'WLX' => 'Luxembourg',
          'WNA' => 'Namur'
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
            'ZH' => 'Zurich',
            'BE' => 'Berne',
            'LU' => 'Lucerne',
            'UR' => 'Uri',
            'SZ' => 'Schwytz',
            'OW' => 'Obwald',
            'NW' => 'Nidwald',
            'GL' => 'Glaris',
            'ZG' => 'Zoug',
            'FR' => 'Fribourg',
            'SO' => 'Soleure',
            'BS' => 'Bâle-Ville',
            'BL' => 'Bâle-Campagne',
            'SH' => 'Schaffhouse',
            'AR' => 'Appenzell Rhodes-Extérieures',
            'AI' => 'Appenzell Rhodes-Intérieures',
            'SG' => 'Saint-Gall',
            'GR' => 'Grisons',
            'AG' => 'Argovie',
            'TG' => 'Thurgovie',
            'TI' => 'Tessin',
            'VD' => 'Vaud',
            'VS' => 'Valais',
            'NE' => 'Neuchâtel',
            'GE' => 'Genève',
            'JU' => 'Jura'
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
