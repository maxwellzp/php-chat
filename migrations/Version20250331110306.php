<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250331110306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD sent_by_id UUID NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN message.sent_by_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA45BB98C FOREIGN KEY (sent_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307FA45BB98C ON message (sent_by_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP CONSTRAINT FK_B6BD307FA45BB98C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B6BD307FA45BB98C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP sent_by_id
        SQL);
    }
}
