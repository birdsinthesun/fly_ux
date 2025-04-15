<?php

namespace Bits\FlyUxBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;

#[AsCallback(table: 'tl_content', target: 'config.onsubmit')]
class ContentSubmitCallbackListener
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function __invoke(DataContainer $dc): void
    {
        $session = System::getContainer()->get('request_stack')->getSession();
        $session->getBag('contao_backend')->delete('OP_ADD_PID');
    }
}