<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181018222138 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE purchase_offer CHANGE project_id project_id INT DEFAULT NULL, CHANGE purchaser_id purchaser_id INT DEFAULT NULL, CHANGE offer_expires_at_utc_date offer_expires_at_utc_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE git_project ADD project_value INT DEFAULT 1000 NOT NULL');
        $this->addSql('ALTER TABLE project_participation CHANGE git_project_id git_project_id INT DEFAULT NULL, CHANGE calculation_utc_datetime calculation_utc_datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE trading CHANGE sell_offer_id sell_offer_id INT DEFAULT NULL, CHANGE transaction_utc_datetime transaction_utc_datetime DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE sell_offer CHANGE project_id project_id INT DEFAULT NULL, CHANGE seller_id seller_id INT DEFAULT NULL, CHANGE offer_starts_utc_date offer_starts_utc_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE offer_expires_at_utc_date offer_expires_at_utc_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE project_participation_id project_participation_id INT DEFAULT NULL, CHANGE trading_id trading_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE git_project DROP project_value');
        $this->addSql('ALTER TABLE project_participation CHANGE git_project_id git_project_id INT DEFAULT NULL, CHANGE calculation_utc_datetime calculation_utc_datetime DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE purchase_offer CHANGE project_id project_id INT DEFAULT NULL, CHANGE purchaser_id purchaser_id INT DEFAULT NULL, CHANGE offer_expires_at_utc_date offer_expires_at_utc_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE sell_offer CHANGE project_id project_id INT DEFAULT NULL, CHANGE seller_id seller_id INT DEFAULT NULL, CHANGE offer_starts_utc_date offer_starts_utc_date DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE offer_expires_at_utc_date offer_expires_at_utc_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE trading CHANGE sell_offer_id sell_offer_id INT DEFAULT NULL, CHANGE transaction_utc_datetime transaction_utc_datetime DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE transaction CHANGE project_participation_id project_participation_id INT DEFAULT NULL, CHANGE trading_id trading_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
    }
}
