<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211118204550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoria (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comentario (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, video_id INT DEFAULT NULL, texto LONGTEXT NOT NULL, fecha DATETIME NOT NULL, INDEX IDX_4B91E702DB38439E (usuario_id), INDEX IDX_4B91E70229C1004E (video_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nick VARCHAR(255) NOT NULL, nombre VARCHAR(255) NOT NULL, apellidos VARCHAR(255) NOT NULL, tema VARCHAR(255) NOT NULL, idioma VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2265B05DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, categoria_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, fecha DATETIME NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, thumbnail VARCHAR(255) NOT NULL, video VARCHAR(255) NOT NULL, INDEX IDX_7CC7DA2CDB38439E (usuario_id), INDEX IDX_7CC7DA2C3397707A (categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E70229C1004E FOREIGN KEY (video_id) REFERENCES video (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C3397707A');
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E702DB38439E');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CDB38439E');
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E70229C1004E');
        $this->addSql('DROP TABLE categoria');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE video');
    }
}
