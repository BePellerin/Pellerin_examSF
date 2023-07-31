<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230728075337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D6493124B5B6 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6498045251F ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649BAD01528 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6493124B5B6 ON user (lastname)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498045251F ON user (secteur)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649BAD01528 ON user (typecontrat)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }
}
