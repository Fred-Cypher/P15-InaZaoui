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


}
