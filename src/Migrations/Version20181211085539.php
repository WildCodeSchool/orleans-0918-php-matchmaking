<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181211085539 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE player');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3752C7DF5E237E06 ON table_event (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24344DAA85C8C9E4 ON format_event (number_of_players)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, firstname VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, phone_number VARCHAR(10) DEFAULT NULL COLLATE utf8mb4_unicode_ci, mail VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP INDEX UNIQ_24344DAA85C8C9E4 ON format_event');
        $this->addSql('DROP INDEX UNIQ_3752C7DF5E237E06 ON table_event');
    }
}
