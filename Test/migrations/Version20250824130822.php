<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250824130822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car ADD driver_id INT NOT NULL');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DC3423909 FOREIGN KEY (driver_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_773DE69DC3423909 ON car (driver_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DC3423909');
        $this->addSql('DROP INDEX IDX_773DE69DC3423909 ON car');
        $this->addSql('ALTER TABLE car DROP driver_id');
    }
}
