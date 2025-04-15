<?php

namespace Bits\FlyUxBundle\Controller;


use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Contao\CoreBundle\Framework\ContaoFramework;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ContentSortingController extends AbstractController
{
    
    // Dependency Injection für den Connection Service
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    #[Route('/contao/_flyux/update-sorting', name: 'flyux_update_sorting', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (!isset($data['updates']) || !is_array($data['updates'])) {
                return new JsonResponse(['error' => 'Ungültige Daten'], 400);
            }
            $container = $this->container;

           

            foreach ($data['updates'] as $item) {
                if (!isset($item['id'], $item['inColumn'], $item['sorting'])) {
                    continue;
                }

                $this->connection
                    ->prepare('UPDATE tl_content SET sorting = ?, inColumn = ? WHERE id = ?')
                    ->executeQuery([$item['sorting'], $item['inColumn'], $item['id']]);
            }

            return new JsonResponse(['success' => true]);

        } catch (\Throwable $e) {
            return new JsonResponse([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
