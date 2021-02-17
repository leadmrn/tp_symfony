<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

// Vos entités
use App\Entity\Client;
use App\Entity\Statistic;
use App\Entity\Beer;
use App\Entity\Category;

class StatisticFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $names = [
            'Jack',
            'James',
            'Pierre',
            'Paul',
            'Boris',
            'Maurice',
            'Éric',
            'Léon',
            'Micha',
            'Samir',
        ];

        //Création de clients
        foreach ($names as $name){
            $client  = new Client();
            $client->setName($name);
            $client->setAge(rand(18, 80));
            $client->setNumberBeer(rand(10,50));
            $manager->persist($client);
        }

        $manager->flush();

        $repoBeer = $manager->getRepository(Beer::class);
        $repoCategory = $manager->getRepository(Category::class);
        $repoClient = $manager->getRepository(Client::class);

        $allBeer = $repoBeer->findAll();
        $allClient = $repoClient->findAll();

        $count = 0;

        //Création de statistiques
        while ($count < 5){

            $beer = $allBeer[rand(0, count($allBeer) - 1)];
            $beerId = $beer->getId();
            $client = $allClient[rand(0, count($allClient) - 1)];
            $beerCat = $repoCategory->findCatNormal($beerId);

            $stat = new Statistic();
            $stat->setBeer($beer);
            $stat->setClient($client);
            $stat->setCategoryId($beerCat[0]->getId());
            $stat->setScore(rand(1, 20));


            $manager->persist($stat);

            $count++;
        }


        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            AppFixtures::class,
        );
    }
}
