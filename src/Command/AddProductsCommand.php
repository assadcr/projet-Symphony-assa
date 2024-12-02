<?php
namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:add-products',
    description: 'Ajoute des produits de démonstration à la base de données.',
)]
class AddProductsCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $products = [
            ['name' => 'Éclat de Lune', 'description' => 'Un parfum envoûtant aux notes de jasmin et de bois de santal', 'price' => 59.99, 'image' => 'parfum_femme_1.jpg', 'publicCible' => 'mixte'],
            ['name' => 'Océan Mystique', 'description' => 'Une fragrance fraîche et aquatique avec des notes d\'agrumes', 'price' => 49.99, 'image' => 'parfum_femme_2.jpg', 'publicCible' => 'mixte'],
            ['name' => 'Velours Noir', 'description' => 'Un parfum sensuel aux notes de vanille et de patchouli', 'price' => 39.99, 'image' => 'parfum_femme_3.jpg', 'publicCible' => 'mixte'],
            ['name' => 'Aura Boisée', 'description' => 'Une fragrance élégante aux notes de cèdre et de vétiver', 'price' => 69.99, 'image' => 'parfum_femme_4.jpg', 'publicCible' => 'mixte'],
            ['name' => 'Fleur de Soie', 'description' => 'Un parfum délicat aux notes de pivoine et de musc blanc', 'price' => 79.99, 'image' => 'parfum_femme_5.jpg', 'publicCible' => 'mixte'],
            ['name' => 'Ambre Doré', 'description' => 'Une fragrance chaude et enveloppante aux notes d\'ambre et de vanille', 'price' => 79.99, 'image' => 'parfum_femme_6.jpg', 'publicCible' => 'mixte', 'slug' => 'ambre-dore'],
            ['name' => 'Brise Marine', 'description' => 'Un parfum frais et vivifiant aux notes marines et d\'agrumes', 'price' => 79.99, 'image' => 'coffrets.jpg', 'publicCible' => 'mixte', 'slug' => 'brise-marine'],
            ['name' => 'Douceur Gourmande', 'description' => 'Une fragrance sucrée aux notes de caramel et de praline', 'price' => 79.99, 'image' => 'coffrets1.jpg', 'publicCible' => 'mixte', 'slug' => 'douceur-gourmande'],
            ['name' => 'Épices d\'Orient', 'description' => 'Un parfum exotique aux notes de safran et de cardamome', 'price' => 79.99, 'image' => 'parfum_homme_1.jpg', 'publicCible' => 'mixte', 'slug' => 'epices-orient'],
        ];

        foreach ($products as $productData) {
            $slug = isset($productData['slug']) ? $productData['slug'] : strtolower(str_replace(' ', '-', $productData['name']));
            $existingProduct = $this->entityManager->getRepository(Product::class)->findOneBy(['slug' => $slug]);
            
            if (!$existingProduct) {
                $product = new Product();
                $product->setName($productData['name']);
                $product->setDescription($productData['description']);
                $product->setPrice($productData['price']);
                $product->setImage($productData['image']);
                $product->setPublicCible($productData['publicCible']);
                $product->setStock(100);
                $product->setSlug($slug);

                $this->entityManager->persist($product);
                $output->writeln("Produit '{$productData['name']}' ajouté.");
            } else {
                $output->writeln("Le produit '{$productData['name']}' existe déjà et sera ignoré.");
            }
        }

        $this->entityManager->flush();

        $output->writeln('Opération terminée.');

        return Command::SUCCESS;
    }
}