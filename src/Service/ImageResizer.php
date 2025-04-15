<?php
namespace Bits\FlyUxBundle\Service;

use Contao\ContaoBundle\Image\ImageFactoryInterface;
use Contao\Image\ResizeConfiguration;
use Contao\File;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ImageResizer
{
    private FilesystemAdapter $cache;
    private $imageFactory;
    private $container;

    public function __construct($container)
    {
        $this->imageFactory =  $container->get('contao.image.factory');
        $this->container =  $container;
        $this->cache = new FilesystemAdapter('image_resizer', 3600, 'assets/cache/images/');
   
    
    }


    public function resizeAndCacheImage(string $sourcePath, int $width, int $height): string
    {
        $cacheKey = 'resize_' . $width . '_' .$height . '_' . basename($sourcePath);
        $cachedImage = $this->cache->getItem($cacheKey);

        if (!$cachedImage->isHit()) {
            $resizeConfig = (new ResizeConfiguration())
                ->setWidth($width)
                ->setHeight($height)
                ->setMode(ResizeConfiguration::MODE_CROP);
          
            $absolutePath = realpath($_SERVER['DOCUMENT_ROOT'] . '/' . $sourcePath);
           if($absolutePath===false){
               
               return '';
           }
            $resultPath = str_replace($sourcePath, '', $absolutePath);
            $objImage = $this->imageFactory->create($absolutePath, $resizeConfig);
            $imageUrl = $objImage->getUrl($resultPath);

            $cachedImage->set($imageUrl);
            $this->cache->save($cachedImage);
        }

        return $cachedImage->get();
    }
}
