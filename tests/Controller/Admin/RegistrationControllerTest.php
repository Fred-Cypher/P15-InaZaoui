<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testAdminCanRegisterNewUser(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        $admin = $userRepository->findOneByEmail('admin@test.com');
        $client->loginUser($admin);

        $crawler = $client->request('GET', '/admin/register');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Créer le compte')->form([
            'registration_form[name]' => 'Nouvel utilisateur Test',
            'registration_form[description]' => 'Description du nouvel utilisateur Test',
            'registration_form[email]' => 'utilisateurTest@test.com',
            'registration_form[plainPassword]' => 'password123',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/admin/guest');
        $client->followRedirect();

        $this->assertSelectorTextContains('.alert-success', 'Le compte de l\'invité a bien été créé.');

        $newUser = $userRepository->findOneBy(['email' => 'utilisateurTest@test.com']);
        $this->assertNotNull($newUser);
        $this->assertEquals('Nouvel utilisateur Test', $newUser->getName());
    }
}
