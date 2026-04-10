<?php

namespace App\Tests\Entity;

use App\Entity\Album;
use App\Entity\Media;
use PHPUnit\Framework\TestCase;

class AlbumTest extends TestCase
{
    public function testAlbumGettersAndSetters(): void
    {
        $album = new Album();
        $name = "Album test";

        $album->setName($name);
        $this->assertSame($name, $album->getName());

        $this->assertNull($album->getId());
    }

    public function testAlbumCollection(): void
    {
        $album = new Album();
        $media = new Media();

        $album->addMedia($media);
        $this->assertCount(1, $album->getMedias());
        $this->assertContains($media, $album->getMedias());
        $this->assertSame($album, $media->getAlbum());

        $album->removeMedia($media);
        $this->assertCount(0, $album->getMedias());
        $this->assertNull($media->getAlbum());
    }
}
