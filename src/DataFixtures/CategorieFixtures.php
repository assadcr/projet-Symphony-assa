<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = ['Parfums', 'Coffrets de senteurs'];

        foreach ($categories as $categorieName) {
            $categorie = new Categorie();
            $categorie->setNom($categorieName);
            $manager->persist($categorie);
        }

        $manager->flush();
    }
}
