<?php

namespace App\Tests\Controller\Admin;

use App\Entity\Album;
use App\Entity\Media;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlbumControllerTest extends WebTestCase
{
    public function testAddAlbum()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneByEmail('admin@test.com');
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/album/add');

        $form = $crawler->selectButton('Ajouter')->form();

        $form['album[name]'] = 'Test album';
        $client->submit($form);

        $this->assertResponseRedirects('/admin/album');
        $client->followRedirect();
    }

    public function testUpdateAlbum()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $albumRepository = static::getContainer()->get(AlbumRepository::class);

        $user = $userRepository->findOneByEmail('admin@test.com');
        $client->loginUser($user);

        $album = $albumRepository->findOneBy([]);
        $albumId = $album->getId();

        $crawler = $client->request('GET', "/admin/album/update/$albumId");
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Modifier')->form([
            'album[name]' => 'Album mis à jour'
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/admin/album');
        $client->followRedirect();

        $updatedAlbum = $albumRepository->find($albumId);
        $this->assertEquals('Album mis à jour', $updatedAlbum->getName());
    }

    public function testDeleteAlbum()
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $albumRepository = static::getContainer()->get(AlbumRepository::class);
        $mediaRepository = static::getContainer()->get(MediaRepository::class);

        $user = $userRepository->findOneByEmail('admin@test.com');
        $client->loginUser($user);

        $album = new Album();
        $album->setName('Test album');
        $entityManager->persist($album);

        $media = new Media();
        $media->setTitle('Photo test');
        $media->setPath('test.jpg');
        $media->setUser($user);
        $media->setAlbum($album);
        $entityManager->persist($media);

        $entityManager->flush();

        $albumId = $album->getId();
        $mediaId = $media->getId();

        $client->request('POST', "/admin/album/delete/$albumId");
        $this->assertResponseRedirects('/admin/album');
        $client->followRedirect();

        $this->assertNull($albumRepository->find($albumId));
        $this->assertNull($mediaRepository->find($mediaId));
    }
}
