<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231017154737 extends AbstractMigration
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
        $this->addSql('ALTER TABLE ticket CHANGE DATE_VENTE DATE_VENTE DATETIME NOT NULL');
        $this->addSql('ALTER TABLE vendre DROP FOREIGN KEY FK_VENDRE_TICKET');
        $this->addSql('DROP INDEX IDX_899DDA2D29C6077F269685E0 ON vendre');
        $this->addSql('DROP INDEX `primary` ON vendre');
        $this->addSql('ALTER TABLE vendre CHANGE ANNEE ANNEE INT DEFAULT NULL, CHANGE NUMERO_TICKET NUMERO_TICKET INT DEFAULT NULL, CHANGE ID_ARTICLE ID_ARTICLE INT AUTO_INCREMENT NOT NULL, CHANGE PRIX_VENTE PRIX_VENTE NUMERIC(10, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE vendre ADD CONSTRAINT FK_899DDA2D269685E029C6077F FOREIGN KEY (NUMERO_TICKET, ANNEE) REFERENCES ticket (NUMERO_TICKET, ANNEE)');
        $this->addSql('CREATE INDEX FK_VENDRE_TICKET ON vendre (NUMERO_TICKET, ANNEE)');
        $this->addSql('ALTER TABLE vendre ADD PRIMARY KEY (ID_ARTICLE)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE article CHANGE ID_MARQUE ID_MARQUE INT NOT NULL');
        $this->addSql('ALTER TABLE article RENAME INDEX idx_23a0e66f97a9a35 TO FK_ARTICLE_TYPEBIERE');
        $this->addSql('ALTER TABLE article RENAME INDEX idx_23a0e66107951fc TO FK_ARTICLE_COULEUR');
        $this->addSql('ALTER TABLE article RENAME INDEX idx_23a0e66f7270350 TO FK_ARTICLE_MARQUE');
        $this->addSql('ALTER TABLE pays CHANGE ID_CONTINENT ID_CONTINENT INT NOT NULL');
        $this->addSql('ALTER TABLE ticket CHANGE DATE_VENTE DATE_VENTE DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE vendre MODIFY ID_ARTICLE INT NOT NULL');
        $this->addSql('ALTER TABLE vendre DROP FOREIGN KEY FK_899DDA2D269685E029C6077F');
        $this->addSql('DROP INDEX FK_VENDRE_TICKET ON vendre');
        $this->addSql('DROP INDEX `PRIMARY` ON vendre');
        $this->addSql('ALTER TABLE vendre CHANGE ID_ARTICLE ID_ARTICLE INT NOT NULL, CHANGE PRIX_VENTE PRIX_VENTE NUMERIC(19, 4) DEFAULT \'NULL\', CHANGE NUMERO_TICKET NUMERO_TICKET INT NOT NULL, CHANGE ANNEE ANNEE INT NOT NULL');
        $this->addSql('ALTER TABLE vendre ADD CONSTRAINT FK_VENDRE_TICKET FOREIGN KEY (ANNEE, NUMERO_TICKET) REFERENCES ticket (ANNEE, NUMERO_TICKET) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_899DDA2D29C6077F269685E0 ON vendre (ANNEE, NUMERO_TICKET)');
        $this->addSql('ALTER TABLE vendre ADD PRIMARY KEY (ANNEE, NUMERO_TICKET, ID_ARTICLE)');
    }
}
