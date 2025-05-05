<?php
namespace Bits\FlyUxBundle\Callback;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Callback\CallbackFinder;

class ContentLayoutMode
{
    public function __construct(private readonly CallbackFinder $callbackFinder, private readonly ContaoFramework $framework)
    {
    }

    public function addSettings(): array
    {
        $this->framework->initialize();

        $callbacks = $this->callbackFinder->find('tl_content', 'view.settings');

        $settings = [];

        foreach ($callbacks as [$callback, $method]) {
            $instance = \is_string($callback) ? new $callback() : $callback;
            $settings = array_merge($settings, $instance->$method());
        }

        return $settings;
    }
}
