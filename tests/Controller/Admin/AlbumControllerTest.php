<?php

namespace App\Tests\Controller\Admin;

use App\Repository\AlbumRepository;
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
}
