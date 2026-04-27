<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = new Client();
        $client->setName('Ayah Abuhaltam');
        $client->setEmail('ayahhaltam@gmail.com');
        $client->setCompany('Telution');
        $client->setAddress('Germany');
        $manager->persist($client);

        $client2 = new Client();
        $client2->setName('Abd Alrhman');
        $client2->setEmail('abdalrhman@gmail.com');
        $client2->setCompany('Zain');
        $client2->setAddress('Jordan');
        $manager->persist($client2);

        $client3 = new Client();
        $client3->setName('Sarah');
        $client3->setEmail('sarah@gmail.com');
        $client3->setCompany('ProgressSoft');
        $client3->setAddress('Jordan');
        $manager->persist($client3);

        
        $manager->flush();
    }
}
