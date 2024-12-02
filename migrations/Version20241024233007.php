<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241024233007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create commande and commande_product tables if they do not exist';
    }

    public function up(Schema $schema): void
    {
        // Vérifiez si la table commande existe déjà
        if (!$schema->hasTable('commande')) {
            $this->addSql('CREATE TABLE commande (
                id INT AUTO_INCREMENT NOT NULL,
                user_id INT NOT NULL,
                date DATETIME NOT NULL,
                status VARCHAR(255) NOT NULL,
                total DOUBLE PRECISION NOT NULL,
                INDEX IDX_6EEAA67DA76ED395 (user_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        }

        // Vérifiez si la table commande_product existe déjà
        if (!$schema->hasTable('commande_product')) {
            $this->addSql('CREATE TABLE commande_product (
                commande_id INT NOT NULL,
                product_id INT NOT NULL,
                INDEX IDX_25F1760D82EA2E54 (commande_id),
                INDEX IDX_25F1760D4584665A (product_id),
                PRIMARY KEY(commande_id, product_id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE commande_product ADD CONSTRAINT FK_25F1760D82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE commande_product ADD CONSTRAINT FK_25F1760D4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        }
    }

    public function down(Schema $schema): void
    {
        // Supprimez les contraintes et les tables en cas de rollback
        if ($schema->hasTable('commande_product')) {
            $this->addSql('ALTER TABLE commande_product DROP FOREIGN KEY FK_25F1760D82EA2E54');
            $this->addSql('ALTER TABLE commande_product DROP FOREIGN KEY FK_25F1760D4584665A');
            $this->addSql('DROP TABLE commande_product');
        }

        if ($schema->hasTable('commande')) {
            $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
            $this->addSql('DROP TABLE commande');
        }
    }
}