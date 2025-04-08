<?php
namespace Bits\FlyUxBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FlyUxBundle extends Bundle
{
      public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
