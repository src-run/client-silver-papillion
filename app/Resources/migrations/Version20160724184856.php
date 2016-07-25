<?php

namespace SR\AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160724184856 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customer_order (id VARCHAR(255) NOT NULL, shipping_address_id VARCHAR(255) DEFAULT NULL, billing_address_id VARCHAR(255) DEFAULT NULL, user_id INT DEFAULT NULL, created_on DATETIME DEFAULT NULL, updated_on DATETIME DEFAULT NULL, shipped_on DATETIME DEFAULT NULL, shipping LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', UNIQUE INDEX UNIQ_3B1CE6A34D4CFF2B (shipping_address_id), UNIQUE INDEX UNIQ_3B1CE6A379D0C0E4 (billing_address_id), INDEX IDX_3B1CE6A3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_order_item (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, order_id VARCHAR(255) DEFAULT NULL, quantity SMALLINT NOT NULL, tax_rate NUMERIC(10, 0) NOT NULL, INDEX IDX_AF231B8B4584665A (product_id), INDEX IDX_AF231B8B8D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, created_on DATETIME DEFAULT NULL, updated_on DATETIME DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, tags LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', enabled TINYINT(1) NOT NULL, featured TINYINT(1) NOT NULL, image VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_mapto_product (product_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_ABFDFA534584665A (product_id), INDEX IDX_ABFDFA5312469DE2 (category_id), PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, address_id VARCHAR(255) DEFAULT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE address (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, address LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', city VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, zip VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, enabled TINYINT(1) NOT NULL, featured TINYINT(1) NOT NULL, name VARCHAR(510) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(510) DEFAULT NULL, INDEX IDX_CDFC7356727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_order ADD CONSTRAINT FK_3B1CE6A34D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE customer_order ADD CONSTRAINT FK_3B1CE6A379D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE customer_order ADD CONSTRAINT FK_3B1CE6A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE customer_order_item ADD CONSTRAINT FK_AF231B8B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE customer_order_item ADD CONSTRAINT FK_AF231B8B8D9F6D38 FOREIGN KEY (order_id) REFERENCES customer_order (id)');
        $this->addSql('ALTER TABLE category_mapto_product ADD CONSTRAINT FK_ABFDFA534584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_mapto_product ADD CONSTRAINT FK_ABFDFA5312469DE2 FOREIGN KEY (category_id) REFERENCES product_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC7356727ACA70 FOREIGN KEY (parent_id) REFERENCES product_category (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer_order_item DROP FOREIGN KEY FK_AF231B8B8D9F6D38');
        $this->addSql('ALTER TABLE customer_order_item DROP FOREIGN KEY FK_AF231B8B4584665A');
        $this->addSql('ALTER TABLE category_mapto_product DROP FOREIGN KEY FK_ABFDFA534584665A');
        $this->addSql('ALTER TABLE customer_order DROP FOREIGN KEY FK_3B1CE6A3A76ED395');
        $this->addSql('ALTER TABLE customer_order DROP FOREIGN KEY FK_3B1CE6A34D4CFF2B');
        $this->addSql('ALTER TABLE customer_order DROP FOREIGN KEY FK_3B1CE6A379D0C0E4');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F5B7AF75');
        $this->addSql('ALTER TABLE category_mapto_product DROP FOREIGN KEY FK_ABFDFA5312469DE2');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC7356727ACA70');
        $this->addSql('DROP TABLE customer_order');
        $this->addSql('DROP TABLE customer_order_item');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE category_mapto_product');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE product_category');
    }
}
