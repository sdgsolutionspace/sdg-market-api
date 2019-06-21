<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190619183432 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE git_project ADD created_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE git_project ADD CONSTRAINT FK_AC0C61CEDE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AC0C61CEDE12AB56 ON git_project (created_by)');
        $this->addSql('ALTER TABLE purchase_offer CHANGE project_id project_id INT DEFAULT NULL, CHANGE purchaser_id purchaser_id INT DEFAULT NULL, CHANGE offer_starts_utc_date offer_starts_utc_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE offer_expires_at_utc_date offer_expires_at_utc_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE project_participation CHANGE git_project_id git_project_id INT DEFAULT NULL, CHANGE calculation_utc_datetime calculation_utc_datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE committer_email committer_email VARCHAR(255) DEFAULT NULL, CHANGE committer_username committer_username VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sell_offer CHANGE project_id project_id INT DEFAULT NULL, CHANGE seller_id seller_id INT DEFAULT NULL, CHANGE offer_starts_utc_date offer_starts_utc_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE offer_expires_at_utc_date offer_expires_at_utc_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE from_user_id from_user_id INT DEFAULT NULL, CHANGE to_user_id to_user_id INT DEFAULT NULL, CHANGE project_id project_id INT DEFAULT NULL, CHANGE sell_offer_id sell_offer_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE git_project DROP FOREIGN KEY FK_AC0C61CEDE12AB56');
        $this->addSql('DROP INDEX IDX_AC0C61CEDE12AB56 ON git_project');
        $this->addSql('ALTER TABLE git_project DROP created_by');
        $this->addSql('ALTER TABLE project_participation CHANGE git_project_id git_project_id INT DEFAULT NULL, CHANGE calculation_utc_datetime calculation_utc_datetime DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE committer_email committer_email VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE committer_username committer_username VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE purchase_offer CHANGE project_id project_id INT DEFAULT NULL, CHANGE purchaser_id purchaser_id INT DEFAULT NULL, CHANGE offer_starts_utc_date offer_starts_utc_date DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE offer_expires_at_utc_date offer_expires_at_utc_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE sell_offer CHANGE project_id project_id INT DEFAULT NULL, CHANGE seller_id seller_id INT DEFAULT NULL, CHANGE offer_starts_utc_date offer_starts_utc_date DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE offer_expires_at_utc_date offer_expires_at_utc_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE transaction CHANGE from_user_id from_user_id INT DEFAULT NULL, CHANGE to_user_id to_user_id INT DEFAULT NULL, CHANGE project_id project_id INT DEFAULT NULL, CHANGE sell_offer_id sell_offer_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT \'current_timestamp()\'');
    }
}
