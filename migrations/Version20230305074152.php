<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305074152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE property (id INT AUTO_INCREMENT NOT NULL, transaction_type_id INT NOT NULL, type_bien_id INT NOT NULL, owner_id INT NOT NULL, title VARCHAR(255) NOT NULL, resume LONGTEXT NOT NULL, description LONGTEXT NOT NULL, price NUMERIC(10, 2) NOT NULL, address VARCHAR(255) NOT NULL, address_comp VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(25) NOT NULL, town VARCHAR(80) NOT NULL, country VARCHAR(100) NOT NULL, display_location VARCHAR(255) DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, logitude DOUBLE PRECISION DEFAULT NULL, INDEX IDX_8BF21CDEB3E6B071 (transaction_type_id), INDEX IDX_8BF21CDE95B4D7FA (type_bien_id), INDEX IDX_8BF21CDE7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEB3E6B071 FOREIGN KEY (transaction_type_id) REFERENCES type_transaction (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE95B4D7FA FOREIGN KEY (type_bien_id) REFERENCES type_bien (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDE7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEB3E6B071');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE95B4D7FA');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDE7E3C61F9');
        $this->addSql('DROP TABLE property');
    }
}
