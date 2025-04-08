<?php
namespace Bits\IsoMasonryBundle\Service;

use Contao\System;
use Contao\Image;
use Contao\File;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ImageResizer
{
     private $cache;

    public function __construct()
    {
        // Erstelle einen Cache-Adapter, der das Dateisystem verwendet
        $this->cache = new FilesystemAdapter('image_resizer', 3600, 'assets/cache/images/');
    }
    // Funktion zum Verkleinern und Cachen des Bildes
    public function resizeAndCacheImage($sourcePath, $width, $height)
    {
        $cacheKey = 'resize_' . $width . '_' .$height . '_' . basename($sourcePath);
        $cachedImage = $this->cache->getItem($cacheKey);
        if (!$cachedImage->isHit()) {
            // Wenn es nicht im Cache ist, das Bild verarbeiten
            $image = Image::get($sourcePath,$width, $height);
           // $image->resize($width, $height);

            // Bild in den Cache speichern
            $cachedImage->set($image);
            $this->cache->save($cachedImage);
        }

        return $cachedImage->get(); // Das bearbeitete Bild zurückgeben
    }
}
