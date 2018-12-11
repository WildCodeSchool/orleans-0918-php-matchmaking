<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181129171348 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE timer CHANGE round_seconds round_seconds INT DEFAULT NULL, CHANGE pause_minutes pause_minutes INT DEFAULT NULL, CHANGE pause_seconds pause_seconds INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event CHANGE round_minutes round_minutes INT NOT NULL, CHANGE round_seconds round_seconds INT NOT NULL, CHANGE pause_minutes pause_minutes INT NOT NULL, CHANGE pause_seconds pause_seconds INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event CHANGE round_minutes round_minutes INT DEFAULT NULL, CHANGE round_seconds round_seconds INT DEFAULT NULL, CHANGE pause_minutes pause_minutes INT DEFAULT NULL, CHANGE pause_seconds pause_seconds INT DEFAULT NULL');
        $this->addSql('ALTER TABLE timer CHANGE round_seconds round_seconds INT NOT NULL, CHANGE pause_minutes pause_minutes INT NOT NULL, CHANGE pause_seconds pause_seconds INT NOT NULL');
    }
}
