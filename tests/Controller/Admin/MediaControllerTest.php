<?php

namespace App\Tests\Controller\Admin;

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

        $this->assertResponseRedirects('/admin/media');
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'L\'image a bien été ajoutée');
    }
}
