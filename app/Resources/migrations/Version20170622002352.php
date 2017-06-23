<?php

namespace SR\AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170622002352 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customer_order_coupon (id INT AUTO_INCREMENT NOT NULL, expiration DATETIME DEFAULT NULL, featured TINYINT(1) DEFAULT NULL, enabled TINYINT(1) NOT NULL, name VARCHAR(250) NOT NULL, code VARCHAR(250) NOT NULL, description LONGTEXT DEFAULT NULL, discount_dollars DOUBLE PRECISION DEFAULT NULL, discount_percent DOUBLE PRECISION DEFAULT NULL, maximum_value DOUBLE PRECISION DEFAULT NULL, minimum_requirement DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE ip2location_db11');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ip2location_db11 (ip_from INT UNSIGNED DEFAULT NULL, ip_to INT UNSIGNED DEFAULT NULL, country_code CHAR(2) DEFAULT NULL COLLATE utf8_bin, country_name VARCHAR(64) DEFAULT NULL COLLATE utf8_bin, region_name VARCHAR(128) DEFAULT NULL COLLATE utf8_bin, city_name VARCHAR(128) DEFAULT NULL COLLATE utf8_bin, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, zip_code VARCHAR(30) DEFAULT NULL COLLATE utf8_bin, time_zone VARCHAR(8) DEFAULT NULL COLLATE utf8_bin, INDEX idx_ip_from (ip_from), INDEX idx_ip_to (ip_to), INDEX idx_ip_from_to (ip_from, ip_to)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE customer_order_coupon');
    }
}
