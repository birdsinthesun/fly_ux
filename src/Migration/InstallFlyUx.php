<?php

namespace Bits\FlyUxBundle\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

class InstallFlyUx extends AbstractMigration
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getName(): string
    {
        return 'FlyUx: Fix tl_content.pid to reference tl_page instead of tl_article';
    }

    public function run(): MigrationResult
    {
        // 🧱 Füge 'ptable' und 'inColumn' zu tl_content hinzu, falls sie fehlen
        $schemaManager = method_exists($this->connection, 'createSchemaManager')
            ? $this->connection->createSchemaManager()
            : $this->connection->getSchemaManager();


        $columns = $schemaManager->listTableColumns('tl_layout');
        if (!array_key_exists('be_grid', $columns)) {
            $this->connection->executeStatement(
                "ALTER TABLE tl_layout ADD be_grid blob NULL"
            );
        }

        $columns = $schemaManager->listTableColumns('tl_content');

        if (!array_key_exists('ptable', $columns)) {
            $this->connection->executeStatement(
                "ALTER TABLE tl_content ADD ptable VARCHAR(64) COLLATE ascii_bin NOT NULL DEFAULT 'tl_content'"
            );
        }

        if (!array_key_exists('inColumn', $columns)) {
            $this->connection->executeStatement(
                "ALTER TABLE tl_content ADD inColumn VARCHAR(32) NOT NULL DEFAULT 'main'"
            );
        }

        // 🧪 Prüfen ob tl_article existiert
        if (!$this->tableExists('tl_article')) {
            return $this->createResult(false, 'Tabelle tl_article ist nicht vorhanden.');
        }

        // 📑 Artikel holen
        $articles = $this->connection->fetchAllAssociative('SELECT id, pid, inColumn FROM tl_article');

        if (empty($articles)) {
            return $this->createResult(false, 'Keine Artikel vorhanden.');
        }

        $updatedCount = 0;

        // 🔁 Inhalte anpassen
        foreach ($articles as $article) {
            $articleId = (int) $article['id'];
            $pageId = (int) $article['pid'];
            $column = $article['inColumn'];

            $contentItems = $this->connection->fetchAllAssociative(
                'SELECT id FROM tl_content WHERE pid = ?',
                [$articleId]
            );

            foreach ($contentItems as $item) {
                $this->connection->update(
                    'tl_content',
                    [
                        'pid' => $pageId,
                        'ptable' => 'tl_page',
                        'inColumn' => $column,
                    ],
                    ['id' => (int) $item['id']]
                );

                $updatedCount++;
            }
        }

        // 🧹 Artikel-Tabelle leeren
        $this->connection->executeStatement('TRUNCATE tl_article');

        return $this->createResult(true, "$updatedCount Inhalte migriert und tl_article geleert.");
    }

    private function tableExists(string $table): bool
    {
        $schemaManager = method_exists($this->connection, 'createSchemaManager')
            ? $this->connection->createSchemaManager()
            : $this->connection->getSchemaManager();

        return $schemaManager->tablesExist([$table]);
    }

    public function shouldRun(): bool
    {
        return (!empty($this->connection->fetchAllAssociative('SELECT id FROM tl_article'))&&$this->tableExists('tl_article'));
    }
}
