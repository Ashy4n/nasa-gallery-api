<?php

namespace App\DataFixtures;

use App\Entity\Camera;
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
        $rover1->setName("Curiosity");
        $rover1->setCameras([$camera1, $camera2]);
        $manager->persist($rover1);

        $rover2 = new Rover();
        $rover2->setName("Opportunity");
        $rover2->setCameras([$camera1, $camera2]);
        $manager->persist($rover2);

        $rover3 = new Rover();
        $rover3->setName("Spirit");
        $rover3->setCameras([$camera1, $camera2]);
        $manager->persist($rover3);

        $manager->flush();
    }
}
