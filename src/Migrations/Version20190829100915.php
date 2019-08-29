<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190829100915 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E70246844BA6');
        $this->addSql('ALTER TABLE pregunta DROP FOREIGN KEY FK_AEE0E1F746844BA6');
        $this->addSql('ALTER TABLE resultado DROP FOREIGN KEY FK_B2ED91C46844BA6');
        $this->addSql('ALTER TABLE respuesta DROP FOREIGN KEY FK_6C6EC5EE31A5801E');
        $this->addSql('ALTER TABLE sorteo DROP FOREIGN KEY FK_705F75E0FB5CD01B');
        $this->addSql('ALTER TABLE sorteo_usuario DROP FOREIGN KEY FK_6FA7D120663FD436');
        $this->addSql('ALTER TABLE sorteo DROP FOREIGN KEY FK_705F75E0A338CEA5');
        $this->addSql('ALTER TABLE sorteo_usuario DROP FOREIGN KEY FK_6FA7D120DB38439E');
        $this->addSql('CREATE TABLE poll (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, img VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prize (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, imagen VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, poll_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, explanation VARCHAR(255) NOT NULL, min_val INT NOT NULL, max_val INT NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_136AC1133C947C0F (poll_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, poll_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_9474526C3C947C0F (poll_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lottery (id INT AUTO_INCREMENT NOT NULL, prize_id INT DEFAULT NULL, ganador_id INT DEFAULT NULL, img VARCHAR(255) NOT NULL, fecha DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_BA1BEE83BBE43214 (prize_id), INDEX IDX_BA1BEE83A338CEA5 (ganador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lottery_user (lottery_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_1E0950B8CFAA77DD (lottery_id), INDEX IDX_1E0950B8A76ED395 (user_id), PRIMARY KEY(lottery_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, value INT NOT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, poll_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B6F7494E3C947C0F (poll_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1133C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C3C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lottery ADD CONSTRAINT FK_BA1BEE83BBE43214 FOREIGN KEY (prize_id) REFERENCES prize (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lottery ADD CONSTRAINT FK_BA1BEE83A338CEA5 FOREIGN KEY (ganador_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE lottery_user ADD CONSTRAINT FK_1E0950B8CFAA77DD FOREIGN KEY (lottery_id) REFERENCES lottery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lottery_user ADD CONSTRAINT FK_1E0950B8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E3C947C0F FOREIGN KEY (poll_id) REFERENCES poll (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE comentario');
        $this->addSql('DROP TABLE encuesta');
        $this->addSql('DROP TABLE pregunta');
        $this->addSql('DROP TABLE premio');
        $this->addSql('DROP TABLE respuesta');
        $this->addSql('DROP TABLE resultado');
        $this->addSql('DROP TABLE sorteo');
        $this->addSql('DROP TABLE sorteo_usuario');
        $this->addSql('DROP TABLE usuario');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1133C947C0F');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C3C947C0F');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E3C947C0F');
        $this->addSql('ALTER TABLE lottery DROP FOREIGN KEY FK_BA1BEE83BBE43214');
        $this->addSql('ALTER TABLE lottery_user DROP FOREIGN KEY FK_1E0950B8CFAA77DD');
        $this->addSql('ALTER TABLE lottery DROP FOREIGN KEY FK_BA1BEE83A338CEA5');
        $this->addSql('ALTER TABLE lottery_user DROP FOREIGN KEY FK_1E0950B8A76ED395');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('CREATE TABLE comentario (id INT AUTO_INCREMENT NOT NULL, encuesta_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_4B91E70246844BA6 (encuesta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE encuesta (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, img VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE pregunta (id INT AUTO_INCREMENT NOT NULL, encuesta_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, text VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, updated_at DATETIME DEFAULT NULL, INDEX IDX_AEE0E1F746844BA6 (encuesta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE premio (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, imagen VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE respuesta (id INT AUTO_INCREMENT NOT NULL, pregunta_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, value INT NOT NULL, INDEX IDX_6C6EC5EE31A5801E (pregunta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE resultado (id INT AUTO_INCREMENT NOT NULL, encuesta_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, image VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, explanation VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, min_val INT NOT NULL, max_val INT NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B2ED91C46844BA6 (encuesta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sorteo (id INT AUTO_INCREMENT NOT NULL, premio_id INT DEFAULT NULL, ganador_id INT DEFAULT NULL, img VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, fecha DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_705F75E0FB5CD01B (premio_id), INDEX IDX_705F75E0A338CEA5 (ganador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sorteo_usuario (sorteo_id INT NOT NULL, usuario_id INT NOT NULL, INDEX IDX_6FA7D120DB38439E (usuario_id), INDEX IDX_6FA7D120663FD436 (sorteo_id), PRIMARY KEY(sorteo_id, usuario_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E70246844BA6 FOREIGN KEY (encuesta_id) REFERENCES encuesta (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pregunta ADD CONSTRAINT FK_AEE0E1F746844BA6 FOREIGN KEY (encuesta_id) REFERENCES encuesta (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE respuesta ADD CONSTRAINT FK_6C6EC5EE31A5801E FOREIGN KEY (pregunta_id) REFERENCES pregunta (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resultado ADD CONSTRAINT FK_B2ED91C46844BA6 FOREIGN KEY (encuesta_id) REFERENCES encuesta (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sorteo ADD CONSTRAINT FK_705F75E0A338CEA5 FOREIGN KEY (ganador_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE sorteo ADD CONSTRAINT FK_705F75E0FB5CD01B FOREIGN KEY (premio_id) REFERENCES premio (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sorteo_usuario ADD CONSTRAINT FK_6FA7D120663FD436 FOREIGN KEY (sorteo_id) REFERENCES sorteo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sorteo_usuario ADD CONSTRAINT FK_6FA7D120DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE poll');
        $this->addSql('DROP TABLE prize');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE lottery');
        $this->addSql('DROP TABLE lottery_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE question');
    }
}
