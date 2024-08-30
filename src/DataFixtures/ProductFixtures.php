<?php

// src/DataFixtures/ProduitFixtures.php
namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProduitFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $produits = [
            ['nom' => 'Parfum Homme 1', 'publicCible' => 'homme', 'prix' => 59.99],
            ['nom' => 'Coffret Homme 1', 'publicCible' => 'homme', 'prix' => 79.99],
            ['nom' => 'Parfum Femme 1', 'publicCible' => 'femme', 'prix' => 69.99],
            ['nom' => 'Coffret Femme 1', 'publicCible' => 'femme', 'prix' => 89.99],
            ['nom' => 'Parfum Enfant 1', 'publicCible' => 'enfant', 'prix' => 39.99],
            ['nom' => 'Parfum Bébé 1', 'publicCible' => 'bebe', 'prix' => 29.99],
        ];

        foreach ($produits as $produitData) {
            $produit = new Product();
            $produit->setName($produitData['nom']);
            $produit->setPublicCible($produitData['publicCible']);
            $produit->setPrice($produitData['prix']);
            $manager->persist($produit);
        }

        $manager->flush();
    }
}
