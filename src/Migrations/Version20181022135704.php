<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181022135704 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM project_participation');
        $this->addSql('ALTER TABLE project_participation DROP FOREIGN KEY FK_7FC47549A76ED395');
        $this->addSql('DROP INDEX IDX_7FC47549A76ED395 ON project_participation');
        $this->addSql('ALTER TABLE project_participation ADD transaction_id INT NOT NULL, DROP user_id');
        $this->addSql('ALTER TABLE project_participation ADD CONSTRAINT FK_7FC475492FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('CREATE INDEX IDX_7FC475492FC0CB0F ON project_participation (transaction_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project_participation DROP FOREIGN KEY FK_7FC475492FC0CB0F');
        $this->addSql('DROP INDEX IDX_7FC475492FC0CB0F ON project_participation');
        $this->addSql('ALTER TABLE project_participation ADD user_id INT DEFAULT NULL, DROP transaction_id');
        $this->addSql('ALTER TABLE project_participation ADD CONSTRAINT FK_7FC47549A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7FC47549A76ED395 ON project_participation (user_id)');
    }
}
