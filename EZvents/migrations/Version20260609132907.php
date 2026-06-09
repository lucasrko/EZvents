<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260609132907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD code_postal VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE event DROP code_postale');
        $this->addSql('ALTER TABLE event ALTER ville SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD code_postale VARCHAR(5) DEFAULT NULL');
        $this->addSql('ALTER TABLE event DROP code_postal');
        $this->addSql('ALTER TABLE event ALTER ville DROP NOT NULL');
    }
}
