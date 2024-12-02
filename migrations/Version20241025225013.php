<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241025225013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add client_name and client_email columns to order table if they do not exist';
    }

    public function up(Schema $schema): void
    {
        // Vérifiez si la colonne client_name existe déjà
        if (!$schema->getTable('order')->hasColumn('client_name')) {
            $this->addSql('ALTER TABLE `order` ADD client_name VARCHAR(255) NOT NULL');
        }

        // Vérifiez si la colonne client_email existe déjà
        if (!$schema->getTable('order')->hasColumn('client_email')) {
            $this->addSql('ALTER TABLE `order` ADD client_email VARCHAR(255) NOT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        // Supprimez les colonnes en cas de rollback
        if ($schema->getTable('order')->hasColumn('client_name')) {
            $this->addSql('ALTER TABLE `order` DROP client_name');
        }

        if ($schema->getTable('order')->hasColumn('client_email')) {
            $this->addSql('ALTER TABLE `order` DROP client_email');
        }
    }
}