<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Service;

class MailFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $services = ["Direction", "rh", "com", "dev"];
        for ($i = 0; $i < count($services); $i++) {
            $service = new Service();
            $service->setname($services[$i])
                ->setEmail($services[$i] . "@gmail.com");
            $manager->persist($service);
        }

        $manager->flush();
    }
}
