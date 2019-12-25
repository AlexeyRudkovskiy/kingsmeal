<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191223125927 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('ALTER TABLE orders ADD COLUMN status VARCHAR(16) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__orders AS SELECT id, payment_method, address, house_number, apartment, first_name, last_name, phone_number FROM orders');
        $this->addSql('DROP TABLE orders');
        $this->addSql('CREATE TABLE orders (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, payment_method VARCHAR(16) NOT NULL, address VARCHAR(255) NOT NULL, house_number VARCHAR(8) NOT NULL, apartment VARCHAR(8) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone_number VARCHAR(16) NOT NULL)');
        $this->addSql('INSERT INTO orders (id, payment_method, address, house_number, apartment, first_name, last_name, phone_number) SELECT id, payment_method, address, house_number, apartment, first_name, last_name, phone_number FROM __temp__orders');
        $this->addSql('DROP TABLE __temp__orders');
    }
}
