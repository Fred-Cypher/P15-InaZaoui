<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityTest extends WebTestCase
{
    /**
     * @dataProvider provideSecurityRoutes
     */
    public function testAccessControl(string $url, string $email, int $expectedStatus): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        if($email !== 'anonymous'){
            $user = $userRepository->findOneByEmail($email);
            $client->loginUser($user);
        }
        $client->request('GET', $url);
        $this->assertResponseStatusCodeSame($expectedStatus);
    }

    public function provideSecurityRoutes(): \Generator
    {
        yield 'Guest list - Anonymous' => ['/admin/guest', 'anonymous', 302];
        yield 'Guest list - Admin' => ['/admin/guest', 'admin@test.com', 200];
        yield 'Guest list - Guest User' => ['/admin/guest', 'active@test.com', 403];
        yield 'Media add - Admin' => ['/admin/media/add', 'admin@test.com', 200];
    }

    /**
     * @dataProvider provideLoginScenarios
     */
    public function testLoginSecurity(string $email, bool $shouldSucceed, string $expectedMessage = null): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $token = $crawler->filter('input[name="_csrf_token"]')->attr('value');

        $form = $crawler->selectButton('Connexion')->form([
            '_username' => $email,
            '_password' => 'password',
            '_csrf_token' => $token,
        ]);

        $client->submit($form);

        if($shouldSucceed){
            $this->assertResponseRedirects('/');
        } else {
            $client->followRedirect();
            $this->assertSelectorTextContains('.alert-danger', $expectedMessage);
        }
    }

    public function provideLoginScenarios(): \Generator
    {
        yield 'Login success' => ['active@test.com', true];
        yield 'Login blocked by UserChecker' => ['blocked@test.com', false, 'Votre compte a été suspendu. Veuillez contacter un administrateur'];
    }
}
