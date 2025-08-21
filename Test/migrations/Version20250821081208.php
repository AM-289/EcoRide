<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250821081208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP liscence_plate, CHANGE animal animal TINYINT(1) NOT NULL, CHANGE smoke smoke TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD044F5D008');
        $this->addSql('DROP INDEX IDX_9B3D7CD044F5D008 ON ride');
        $this->addSql('ALTER TABLE ride DROP brand_id, DROP driver, DROP car, DROP duration, CHANGE departure_date daparture_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car ADD liscence_plate VARCHAR(255) NOT NULL, CHANGE animal animal TINYINT(1) DEFAULT NULL, CHANGE smoke smoke TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE ride ADD brand_id INT NOT NULL, ADD driver VARCHAR(255) NOT NULL, ADD car VARCHAR(255) NOT NULL, ADD duration DATETIME NOT NULL, CHANGE daparture_date departure_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD044F5D008 FOREIGN KEY (brand_id) REFERENCES car (id)');
        $this->addSql('CREATE INDEX IDX_9B3D7CD044F5D008 ON ride (brand_id)');
    }
}
