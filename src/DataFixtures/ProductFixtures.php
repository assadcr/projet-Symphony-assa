<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductFixtures extends Fixture
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        echo "Début du chargement des fixtures\n";
        
        $faker = Factory::create('fr_FR');

        echo "Chargement des produits pour femmes\n";
        $this->loadFemmeProducts($manager, $faker);
        
        echo "Chargement des produits pour hommes\n";
        $this->loadHommeProducts($manager, $faker);
        
        echo "Chargement des produits pour enfants et bébés\n";
        $this->loadEnfantBebeProducts($manager, $faker);

        echo "Flush des données\n";
        $manager->flush();
        
        echo "Fin du chargement des fixtures\n";
    }

    private function loadFemmeProducts(ObjectManager $manager, $faker)
    {
        $parfumImages = ['parfum_femme_1.jpg', 'parfum_femme_2.jpg', 'parfum_femme_3.jpg', 'parfum_femme_4.jpg', 'parfum_femme_5.jpg'];
        $coffretImages = ['coffret_huile_1.jpg', 'coffret_huile_2.jpg', 'coffret_huile_3.jpg'];

        for ($i = 0; $i < 10; $i++) {
            $product = $this->createProduct($faker, 'femme', $parfumImages, 30, 150);
            $manager->persist($product);
        }

        for ($i = 0; $i < 5; $i++) {
            $product = $this->createProduct($faker, 'femme', $coffretImages, 20, 80, 'Coffret Huiles Essentielles');
            $manager->persist($product);
        }
    }

    private function loadHommeProducts(ObjectManager $manager, $faker)
    {
        $parfumImages = ['parfum_homme_1.jpg', 'parfum_homme_2.jpg', 'parfum_homme_3.jpg', 'parfum_homme_4.jpg'];

        for ($i = 0; $i < 8; $i++) {
            $product = $this->createProduct($faker, 'homme', $parfumImages, 25, 120);
            $manager->persist($product);
        }
    }

    private function loadEnfantBebeProducts(ObjectManager $manager, $faker)
    {
        $parfumImages = ['parfum_enfant_1.jpg', 'parfum_enfant_2.jpg', 'parfum_bebe_1.jpg', 'parfum_bebe_2.jpg'];

        for ($i = 0; $i < 6; $i++) {
            $publicCible = $faker->randomElement(['enfant', 'bebe']);
            $product = $this->createProduct($faker, $publicCible, $parfumImages, 15, 50);
            $manager->persist($product);
        }
    }

    private function createProduct($faker, $publicCible, $images, $minPrice, $maxPrice, $namePrefix = null)
    {
        $product = new Product();
        $name = $namePrefix ?? $faker->randomElement(['Parfum', 'Eau de Toilette']) . ' ' . $faker->word;
        $product->setName($name);
        $product->setSlug($this->slugger->slug($name)->lower());
        $product->setDescription($faker->paragraph(3));
        $product->setPrice($faker->randomFloat(2, $minPrice, $maxPrice));
        $product->setStock($faker->numberBetween(5, 100));
        $product->setPublicCible($publicCible);
        $product->setImage($faker->randomElement($images));
        $product->setCreatedAt($faker->dateTimeBetween('-1 year', 'now'));
        $product->setUpdatedAt($faker->dateTimeBetween($product->getCreatedAt(), 'now'));

        return $product;
    }
}