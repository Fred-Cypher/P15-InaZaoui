<?php

namespace App\Tests\Entity;

use App\Entity\Album;
use App\Entity\Media;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaTest extends TestCase
{
    public function testMediaGettersAndSetters(): void
    {
        $media = new Media();
        $album = new Album();

        $this->assertNull($media->getId());

        $fakeFile = $this->createMock(UploadedFile::class);

        $media->setFile($fakeFile);
        $this->assertSame($fakeFile, $media->getFile());

        $media->setAlbum($album);
        $this->assertSame($album, $media->getAlbum());

        $media->setTitle('image_test.jpg');
        $this->assertSame('image_test.jpg', $media->getTitle());
    }
}
