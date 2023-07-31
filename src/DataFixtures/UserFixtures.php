<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }



    public function load(ObjectManager $manager): void
    {

        $tab = array(
            array('lastname' => 'Pompon', 'username' => 'Nathalie', 'datesortie' => null, 'roles' => ["ROLE_RH"], 'secteur' => 'RH', 'typecontrat' => 'CDI', 'password' => 'rh123@'),
            array('lastname' => 'Pompon', 'username' => 'Jérôme', 'datesortie' => '04-07-2024', 'roles' => ["ROLE_USER"],  'secteur' => 'Informatique', 'typecontrat' => 'Interim', 'password' => 'jerome'),
            array('lastname' => 'Chiesa', 'username' => 'Josiane', 'datesortie' => '04-07-2025', 'roles' => ["ROLE_USER"], 'secteur' => 'Comptabilité', 'typecontrat' => 'CDD', 'password' => 'josiane'),
            array('lastname' => 'Dupont', 'username' => 'Eric', 'datesortie' => null, 'roles' => ["ROLE_USER"], 'secteur' => 'Direction', 'typecontrat' => 'CDI', 'password' => 'eric')
        );

        foreach ($tab as $row) {
            $user = new User();
            $user->setLastname($row['lastname']);
            $user->setUsername($row['username']);
            $user->setRoles($row['roles']);
            $user->setSecteur($row['secteur']);
            $user->setTypecontrat($row['typecontrat']);
            if ($row['datesortie']) {
                $formattedDateSortie = \DateTime::createFromFormat('d-m-Y', $row['datesortie']);
                if ($formattedDateSortie !== false) {
                    $user->setDatesortie($formattedDateSortie);
                } else {
                    $user->setDatesortie(null);
                }
            } else {
                $user->setDatesortie(null);
            }
            $user->setPassword($this->hasher->hashPassword($user, $row['password']));
            $manager->persist($user);
        }
        $manager->flush();
    }
}
