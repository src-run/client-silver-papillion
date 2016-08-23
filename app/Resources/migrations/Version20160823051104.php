<?php

namespace SR\AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160823051104 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customer_order_item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(510) NOT NULL, sku VARCHAR(510) DEFAULT NULL, count INT NOT NULL, total DOUBLE PRECISION NOT NULL, shipping DOUBLE PRECISION NOT NULL, tax DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_order_mapto_item (order_id INT NOT NULL, order_item_id INT NOT NULL, INDEX IDX_E639A7278D9F6D38 (order_id), INDEX IDX_E639A727E415FB15 (order_item_id), PRIMARY KEY(order_id, order_item_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_order_mapto_item ADD CONSTRAINT FK_E639A7278D9F6D38 FOREIGN KEY (order_id) REFERENCES customer_order (id)');
        $this->addSql('ALTER TABLE customer_order_mapto_item ADD CONSTRAINT FK_E639A727E415FB15 FOREIGN KEY (order_item_id) REFERENCES customer_order_item (id)');
        $this->addSql('ALTER TABLE customer_order ADD name VARCHAR(510) NOT NULL, ADD email VARCHAR(510) NOT NULL, ADD address VARCHAR(510) NOT NULL, ADD city VARCHAR(510) NOT NULL, ADD state VARCHAR(510) NOT NULL, ADD zip VARCHAR(510) NOT NULL, ADD total DOUBLE PRECISION NOT NULL, ADD shipping DOUBLE PRECISION NOT NULL, ADD tax DOUBLE PRECISION NOT NULL, ADD paid TINYINT(1) NOT NULL, ADD reference VARCHAR(510) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer_order_mapto_item DROP FOREIGN KEY FK_E639A727E415FB15');
        $this->addSql('DROP TABLE customer_order_item');
        $this->addSql('DROP TABLE customer_order_mapto_item');
        $this->addSql('ALTER TABLE customer_order DROP name, DROP email, DROP address, DROP city, DROP state, DROP zip, DROP total, DROP shipping, DROP tax, DROP paid, DROP reference');
    }
}
