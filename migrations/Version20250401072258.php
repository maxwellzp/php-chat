<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250401072258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD chat_room_id UUID NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN message.chat_room_id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1819BCFA FOREIGN KEY (chat_room_id) REFERENCES chat_room (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6BD307F1819BCFA ON message (chat_room_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP CONSTRAINT FK_B6BD307F1819BCFA
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B6BD307F1819BCFA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP chat_room_id
        SQL);
    }
}
