<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = [
            'Parfums' => 'Une collection de parfums exquis pour tous les goûts.',
            'Coffrets de senteurs' => 'Des ensembles de parfums soigneusement sélectionnés pour une expérience olfactive complète.'
        ];

        foreach ($categories as $categorieName => $description) {
            $categorie = new Categorie();
            $categorie->setNom($categorieName);
            $categorie->setDescription($description);
            $manager->persist($categorie);
        }

        $manager->flush();
    }
}