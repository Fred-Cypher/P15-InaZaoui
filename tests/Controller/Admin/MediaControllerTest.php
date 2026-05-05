<?php

namespace App\Tests\Controller\Admin;

use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaControllerTest extends WebTestCase
{
    public function testAddMedia(){
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneByEmail('active@test.com');
        $client->loginUser($user);

        $crawler = $client->request('GET', '/admin/media/add');

        $projectRoot = static::getContainer()->getParameter('kernel.project_dir');

        $photoPath = $projectRoot . '/tests/fixtures/test_upload.jpg';
        if (!file_exists(dirname($photoPath))) {
            mkdir(dirname($photoPath), 0777, true);
        }
        $image = imagecreatetruecolor(10, 10);
        imagejpeg($image, $photoPath);
        imagedestroy($image);

        $uploadedFile = new UploadedFile($photoPath, 'test_upload.jpg', 'image/jpeg', null, true);

        $form = $crawler->selectButton('Ajouter')->form([
                'media[title]' => 'Ma photo de test',
                'media[file]' => $uploadedFile
            ]
        );

        $client->submit($form);

        if (file_exists($photoPath)) {
            unlink($photoPath);
        }

        $mediaRepository = static::getContainer()->get(MediaRepository::class);
        $media = $mediaRepository->findOneBy(['title' => 'Ma photo de test']);

        if ($media) {
            $uploadedFilePath = $projectRoot . '/public/' . $media->getPath();
            if (file_exists($uploadedFilePath)) {
                unlink($uploadedFilePath);
            }
        }

        $this->assertResponseRedirects('/admin/media');
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'L\'image a bien été ajoutée');
    }

    public function testAdminCanDeleteMedia(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $mediaRepository = static::getContainer()->get(MediaRepository::class);

        $admin = $userRepository->findOneByEmail('admin@test.com');
        $client->loginUser($admin);

        $media = $mediaRepository->findOneBy([]);
        $this->assertNotNull($media, "Pas de media trouvé pour le test");
        $mediaId = $media->getId();

        $crawler = $client->request('GET', '/admin/media');

        $form = $crawler->filter("form[action*='/admin/media/delete/{$mediaId}']")->form();

        $client->submit($form);

        $this->assertResponseRedirects('/admin/media');
        $client->followRedirect();

        $this->assertNull($mediaRepository->find($mediaId));
    }

    public function testDeleteMediaWithInvalidToken(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $mediaRepository = static::getContainer()->get(MediaRepository::class);

        $admin = $userRepository->findOneByEmail('admin@test.com');
        $client->loginUser($admin);

        $media = $mediaRepository->findOneBy([]);
        $mediaId = $media->getId();

        $client->request('POST', '/admin/media/delete/' . $mediaId, [ '_token' => 'invalid_token' ]);

        $this->assertResponseRedirects('/admin/media');
        $client->followRedirect();

        $this->assertSelectorTextContains('.alert-danger', 'Jeton de sécurité invalide');

        $this->assertNotNull($mediaRepository->find($mediaId));
    }
}
