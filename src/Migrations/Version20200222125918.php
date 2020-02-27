<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200222125918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_E6F097B63B69A9AF');
        $this->addSql('DROP INDEX UNIQ_E6F097B64584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ordered_product AS SELECT id, price, quantity, product_id, variant_id, _order_id FROM ordered_product');
        $this->addSql('DROP TABLE ordered_product');
        $this->addSql('CREATE TABLE ordered_product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INTEGER NOT NULL, product_id INTEGER NOT NULL, variant_id INTEGER DEFAULT NULL, _order_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO ordered_product (id, price, quantity, product_id, variant_id, _order_id) SELECT id, price, quantity, product_id, variant_id, _order_id FROM __temp__ordered_product');
        $this->addSql('DROP TABLE __temp__ordered_product');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__ordered_product AS SELECT id, price, quantity, product_id, variant_id, _order_id FROM ordered_product');
        $this->addSql('DROP TABLE ordered_product');
        $this->addSql('CREATE TABLE ordered_product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INTEGER NOT NULL, product_id INTEGER NOT NULL, variant_id INTEGER DEFAULT NULL, _order_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO ordered_product (id, price, quantity, product_id, variant_id, _order_id) SELECT id, price, quantity, product_id, variant_id, _order_id FROM __temp__ordered_product');
        $this->addSql('DROP TABLE __temp__ordered_product');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6F097B63B69A9AF ON ordered_product (variant_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6F097B64584665A ON ordered_product (product_id)');
    }
}
