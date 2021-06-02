<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210601112741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE hit (id VARCHAR(36) NOT NULL, search_engine VARCHAR(255) NOT NULL, searched_term VARCHAR(255) NOT NULL, domain VARCHAR(255) NOT NULL, created_at DATE NOT NULL --(DC2Type:date_immutable)
        , PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE hit');
    }
}
