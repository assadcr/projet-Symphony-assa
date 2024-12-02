<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class ProductController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class; // Retourne la classe de l'entité Product
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), // Masque l'ID lors de la création/édition
            TextField::new('name', 'Nom du produit'), // Champ pour le nom du produit
            TextEditorField::new('description', 'Description')->hideOnIndex(), // Champ pour la description, masqué dans l'index
            MoneyField::new('price', 'Prix')->setCurrency('EUR'), // Champ pour le prix avec la devise Euro
            IntegerField::new('stock', 'Stock disponible'), // Champ pour le stock disponible
            AssociationField::new('categorie', 'Catégorie'), // Champ pour associer une catégorie au produit
            ImageField::new('image')
                ->setBasePath('uploads/products') // Chemin d'accès à l'image
                ->setUploadDir('public/uploads/products') // Dossier où les images seront téléchargées
                ->setUploadedFileNamePattern('[randomhash].[extension]') // Modèle de nom de fichier pour les images téléchargées
                ->setRequired(false), // Champ non requis
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL) // Ajoute un lien vers les détails dans l'index
            ->add(Crud::PAGE_EDIT, Action::SAVE_AND_ADD_ANOTHER); // Ajoute une option pour sauvegarder et ajouter un autre produit
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Produit') // Label au singulier pour l'entité
            ->setEntityLabelInPlural('Produits') // Label au pluriel pour l'entité
            ->setSearchFields(['name', 'description', 'categorie.name']) // Champs à rechercher dans l'interface admin
            ->setDefaultSort(['name' => 'ASC']); // Tri par défaut par nom de produit en ordre croissant
    }

    #[Route('/admin/product/{id}/show', name: 'admin_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
