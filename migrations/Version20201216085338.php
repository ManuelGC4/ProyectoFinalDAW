<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201216085338 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP autor');
        $this->addSql('ALTER TABLE noticia DROP FOREIGN KEY FK_31205F9614D45BBE');
        $this->addSql('DROP INDEX IDX_31205F9614D45BBE ON noticia');
        $this->addSql('ALTER TABLE noticia DROP autor_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario ADD autor VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE noticia ADD autor_id INT NOT NULL');
        $this->addSql('ALTER TABLE noticia ADD CONSTRAINT FK_31205F9614D45BBE FOREIGN KEY (autor_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_31205F9614D45BBE ON noticia (autor_id)');
    }
}
