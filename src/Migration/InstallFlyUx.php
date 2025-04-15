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
        // Prüfen ob tl_article existiert
        if (!$this->tableExists('tl_article')) {
            return $this->createResult(false, 'Tabelle tl_article ist schon entfernt.');
        }

        // Hol alle Artikel
        $articles = $this->connection->fetchAllAssociative('SELECT id, pid FROM tl_article');

        if (empty($articles)) {
            return $this->createResult(false, 'Keine Datensätze in tl_article gefunden.');
        }

        $updatedCount = 0;

        foreach ($articles as $article) {
            $articleId = (int) $article['id'];
            $pageId = (int) $article['pid'];

            // tl_content mit pid = article.id finden
            $contentItems = $this->connection->fetchAllAssociative(
                'SELECT id FROM tl_content WHERE pid = ?',
                [$articleId]
            );

            foreach ($contentItems as $item) {
                $this->connection->update(
                    'tl_content',
                    ['pid' => $pageId],
                    ['id' => (int) $item['id']]
                );

                $updatedCount++;
            }
        }

        return $this->createResult(true, "$updatedCount Inhalte aktualisiert.");
    }

    private function tableExists(string $table): bool
    {
        $schemaManager = method_exists($this->connection, 'createSchemaManager')
            ? $this->connection->createSchemaManager()
            : $this->connection->getSchemaManager(); // fallback < doctrine/dbal 3

        return $schemaManager->tablesExist([$table]);
    }
}
