<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191230191952 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image_filename VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__orders AS SELECT id, payment_method, address, house_number, apartment, first_name, last_name, phone_number, status FROM orders');
        $this->addSql('DROP TABLE orders');
        $this->addSql('CREATE TABLE orders (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, payment_method VARCHAR(16) NOT NULL COLLATE BINARY, address VARCHAR(255) NOT NULL COLLATE BINARY, house_number VARCHAR(8) NOT NULL COLLATE BINARY, apartment VARCHAR(8) NOT NULL COLLATE BINARY, first_name VARCHAR(255) NOT NULL COLLATE BINARY, last_name VARCHAR(255) NOT NULL COLLATE BINARY, phone_number VARCHAR(16) NOT NULL COLLATE BINARY, status VARCHAR(16) NOT NULL)');
        $this->addSql('INSERT INTO orders (id, payment_method, address, house_number, apartment, first_name, last_name, phone_number, status) SELECT id, payment_method, address, house_number, apartment, first_name, last_name, phone_number, status FROM __temp__orders');
        $this->addSql('DROP TABLE __temp__orders');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, name, price, description, photo_filename FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, price DOUBLE PRECISION NOT NULL, description CLOB NOT NULL, photo_filename VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO product (id, name, price, description, photo_filename) SELECT id, name, price, description, photo_filename FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE category');
        $this->addSql('CREATE TEMPORARY TABLE __temp__orders AS SELECT id, payment_method, address, house_number, apartment, first_name, last_name, phone_number, status FROM orders');
        $this->addSql('DROP TABLE orders');
        $this->addSql('CREATE TABLE orders (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, payment_method VARCHAR(16) NOT NULL, address VARCHAR(255) NOT NULL, house_number VARCHAR(8) NOT NULL, apartment VARCHAR(8) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone_number VARCHAR(16) NOT NULL, status VARCHAR(16) DEFAULT \'""\' NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO orders (id, payment_method, address, house_number, apartment, first_name, last_name, phone_number, status) SELECT id, payment_method, address, house_number, apartment, first_name, last_name, phone_number, status FROM __temp__orders');
        $this->addSql('DROP TABLE __temp__orders');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, name, price, description, photo_filename FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description CLOB DEFAULT \'""\' NOT NULL COLLATE BINARY, photo_filename VARCHAR(255) DEFAULT \'""\' NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO product (id, name, price, description, photo_filename) SELECT id, name, price, description, photo_filename FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
    }
}
