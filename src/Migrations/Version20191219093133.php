<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191219093133 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE "order" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, payment_method VARCHAR(16) NOT NULL, address VARCHAR(255) NOT NULL, house_number VARCHAR(8) NOT NULL, apartment VARCHAR(8) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone_number VARCHAR(16) NOT NULL)');
        $this->addSql('CREATE TABLE ordered_product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INTEGER NOT NULL, product_id INTEGER NOT NULL, variant_id INTEGER DEFAULT NULL, _order_id INTEGER NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6F097B64584665A ON ordered_product (product_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6F097B63B69A9AF ON ordered_product (variant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE ordered_product');
    }
}
