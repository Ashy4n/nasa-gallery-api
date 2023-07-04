<?php

namespace App\DataFixtures;

use App\Entity\Camera;
use App\Entity\Rover;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Setting cameras defined in brief

        $camera1 = new Camera();
        $camera1->setName("FHAZ");
        $manager->persist($camera1);

        $camera2 = new Camera();
        $camera2->setName("RHAZ");
        $manager->persist($camera2);

        // Setting rovers defined in brief

        $rover1 = new Rover();
        $rover1->setName("curiosity");
        $rover1->addCamera($camera1);
        $rover1->addCamera($camera2);
        $manager->persist($rover1);

        $rover2 = new Rover();
        $rover2->setName("opportunity");
        $rover2->addCamera($camera1);
        $rover2->addCamera($camera2);
        $manager->persist($rover2);

        $rover3 = new Rover();
        $rover3->setName("spirit");
        $rover3->addCamera($camera1);
        $rover3->addCamera($camera2);
        $manager->persist($rover3);

        $manager->flush();
    }
}
