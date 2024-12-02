<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241021221256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create favoris table if it does not exist';
    }

    public function up(Schema $schema): void
    {
        // Vérifiez si la table favoris existe déjà
        if (!$schema->hasTable('favoris')) {
            $this->addSql('CREATE TABLE favoris (
                id INT AUTO_INCREMENT NOT NULL,
                user_id INT NOT NULL,
                product_id INT NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                INDEX IDX_8933C432A76ED395 (user_id),
                INDEX IDX_8933C4324584665A (product_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

            $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        }
    }

    public function down(Schema $schema): void
    {
        // Supprimez la table favoris en cas de rollback
        if ($schema->hasTable('favoris')) {
            $this->addSql('DROP TABLE favoris');
        }
    }
}