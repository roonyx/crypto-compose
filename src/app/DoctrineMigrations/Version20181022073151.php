<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181022073151 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE link (id INT AUTO_INCREMENT NOT NULL, short_url_id VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_36AC99F1F1252BC8 (short_url_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE links_logs (link_id INT NOT NULL, log_id INT NOT NULL, INDEX IDX_727F2FD4ADA40271 (link_id), INDEX IDX_727F2FD4EA675D86 (log_id), PRIMARY KEY(link_id, log_id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log_coin_rate (id INT AUTO_INCREMENT NOT NULL, coin_id INT NOT NULL, log_id INT NOT NULL, rate VARCHAR(255) NOT NULL, INDEX IDX_F415587584BBDA7 (coin_id), INDEX IDX_F4155875EA675D86 (log_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coin (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE links_logs ADD CONSTRAINT FK_727F2FD4ADA40271 FOREIGN KEY (link_id) REFERENCES link (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE links_logs ADD CONSTRAINT FK_727F2FD4EA675D86 FOREIGN KEY (log_id) REFERENCES log (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE log_coin_rate ADD CONSTRAINT FK_F415587584BBDA7 FOREIGN KEY (coin_id) REFERENCES coin (id)');
        $this->addSql('ALTER TABLE log_coin_rate ADD CONSTRAINT FK_F4155875EA675D86 FOREIGN KEY (log_id) REFERENCES log (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE links_logs DROP FOREIGN KEY FK_727F2FD4ADA40271');
        $this->addSql('ALTER TABLE links_logs DROP FOREIGN KEY FK_727F2FD4EA675D86');
        $this->addSql('ALTER TABLE log_coin_rate DROP FOREIGN KEY FK_F4155875EA675D86');
        $this->addSql('ALTER TABLE log_coin_rate DROP FOREIGN KEY FK_F415587584BBDA7');
        $this->addSql('DROP TABLE link');
        $this->addSql('DROP TABLE links_logs');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE log_coin_rate');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE coin');
    }
}
