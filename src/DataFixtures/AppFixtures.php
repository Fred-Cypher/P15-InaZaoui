<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

        public function load(ObjectManager $manager): void
    {
        // Admin creation
        $admin = new User();
        $admin->setEmail('admin@test.com')
            ->setName('Ina Admin')
            ->setAdmin(true)
            ->setAuthorised(true)
            ->setPassword($this->hasher->hashPassword($admin, 'password'));
        $manager->persist($admin);

        // Creation of active guest
        $guestActive = new User();
        $guestActive->setEmail('active@test.com')
            ->setName('Camille Actif')
            ->setAdmin(false)
            ->setAuthorised(true)
            ->setPassword($this->hasher->hashPassword($guestActive, 'password'));
        $manager->persist($guestActive);

        // Creation of blocked guest
        $guestBlocked = new User();
        $guestBlocked->setEmail('blocked@test.com')
            ->setName('Claude Bloque')
            ->setAdmin(false)
            ->setAuthorised(false)
            ->setPassword($this->hasher->hashPassword($guestBlocked, 'password'));
        $manager->persist($guestBlocked);

        // Creation of albums linked to Ina
        $albumNames = ['Album 1', 'Album 2', 'Album 3', 'Album 4'];
        $inaAlbums = [];

        foreach ($albumNames as $name) {
            $album = new Album();
            $album->setName($name);
            if (method_exists($album, 'setUser')) {
                $album->setUser($admin);
            }
            $manager->persist($album);
            $inaAlbums[] = $album;
        }

        // Add media to Ina's Portfolio (in her albums)
        for ($i = 1; $i <= 30; $i++) {
            $media = new Media();
            $media->setTitle("Photo Ina " . $i)
                ->setPath("https://picsum.photos/seed/ina" . $i . "/800/600.webp")
                ->setUser($admin)
                ->setAlbum($inaAlbums[array_rand($inaAlbums)]);
            $manager->persist($media);
        }

        // Add media for guests
        $slugger = new AsciiSlugger();

        $guests = [$guestActive, $guestBlocked];

        foreach ($guests as $guest) {
            $slug = strtolower($slugger->slug($guest->getName()));

            for ($j = 1; $j <= 20; $j++) {
                $media = new Media();
                $media->setTitle("Photo Jeune Talent " . $guest->getName() . ' ' . $j)
                    ->setPath("https://picsum.photos/seed/" . $slug . "-" . $j . "/800/600.webp")
                    ->setUser($guest)
                    ->setAlbum(null);

                $manager->persist($media);
            }
        }

        $manager->flush();
    }
}
