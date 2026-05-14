<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GuestControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideGuestActions
     */
    public function testGuestManagement(string $emailTarget, string $expectedMessage): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $admin = $userRepository->findOneByEmail('admin@test.com');
        $client->loginUser($admin);

        $targetUser = $userRepository->findOneByEmail($emailTarget);
        $targetId = $targetUser->getId();

        $client->request('POST', '/admin/guest/toggle/' . $targetId);
        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());

        $crawler = $client->request('GET', '/admin/guest');
        $form = $crawler->filter("form[action*='/admin/guest/delete/$targetId']")->selectButton('Supprimer')->form();
        $client->submit($form);

        $this->assertResponseRedirects('/admin/guest');
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', $expectedMessage);
    }

    public function provideGuestActions(): \Generator
    {
        yield 'Toggle and Delete Camille' => [
            'active@test.com',
            'L\'invité et tous ses médias ont bien été supprimés'
        ];
    }
}
