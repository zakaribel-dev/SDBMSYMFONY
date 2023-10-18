<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231016084914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article CHANGE ID_MARQUE ID_MARQUE INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article RENAME INDEX fk_article_marque TO IDX_23A0E66F7270350');
        $this->addSql('ALTER TABLE article RENAME INDEX fk_article_typebiere TO IDX_23A0E66F97A9A35');
        $this->addSql('ALTER TABLE article RENAME INDEX fk_article_couleur TO IDX_23A0E66107951FC');
        $this->addSql('ALTER TABLE pays CHANGE ID_CONTINENT ID_CONTINENT INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vendre DROP QUANTITE, DROP PRIX_VENTE');
        $this->addSql('ALTER TABLE vendre RENAME INDEX fk_vendre_article TO IDX_899DDA2D2E4ED87F');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE pays CHANGE ID_CONTINENT ID_CONTINENT INT NOT NULL');
        $this->addSql('ALTER TABLE article CHANGE ID_MARQUE ID_MARQUE INT NOT NULL');
        $this->addSql('ALTER TABLE article RENAME INDEX idx_23a0e66f7270350 TO FK_ARTICLE_MARQUE');
        $this->addSql('ALTER TABLE article RENAME INDEX idx_23a0e66f97a9a35 TO FK_ARTICLE_TYPEBIERE');
        $this->addSql('ALTER TABLE article RENAME INDEX idx_23a0e66107951fc TO FK_ARTICLE_COULEUR');
        $this->addSql('ALTER TABLE vendre ADD QUANTITE INT DEFAULT NULL, ADD PRIX_VENTE NUMERIC(19, 4) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE vendre RENAME INDEX idx_899dda2d2e4ed87f TO FK_VENDRE_ARTICLE');
    }
}
