<?php

namespace App\Tests\Controller;

use App\Entity\Album;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    /**
     * @dataProvider providePublicUrls
     */
    public function testPublicPagesAreSuccessful(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    public function providePublicUrls(): iterable
    {
        yield 'Page d\'accueil' => ['/'];
        yield 'Qui suis-je ?' => ['/about'];
        yield 'Portfolio' => ['/portfolio'];
        yield 'Liste des invités' => ['/guests'];
    }

    public function testGuestDetailAndPhotos(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $userRepository = $container->get(UserRepository::class);

        $activeUser = $userRepository->findOneBy(['authorised' => true]);

        $this->assertNotNull($activeUser);

        $client->request('GET', '/guest/' . $activeUser->getId());
        $this->assertResponseIsSuccessful();
    }

    public function testUnauthorisedIsNotFound(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $userRepository = $container->get(UserRepository::class);

        $unauthorisedUser = $userRepository->findOneBy(['authorised' => false]);

        if($unauthorisedUser){
            $client->request('GET', '/guest/' . $unauthorisedUser->getId());

            $this->assertResponseStatusCodeSame(404);
        }
    }

    public function testPortfolioHomeDisplaysAdminMedias(): void
    {
        $client = static::createClient();
        $client->request('GET', '/portfolio');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h3', 'Portfolio');

    }

    public function testPortfolioWithIdDisplaysAlbumMedias(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $em = $container->get('doctrine')->getManager();


        $album = $em->getRepository(Album::class)->findOneBy([]);

        $this->assertNotNull($album, "Aucun album trouvé en base de test. As-tu chargé les fixtures ?");

        $client->request('GET', '/portfolio/' . $album->getId());

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('body', $album->getName());
    }
}
