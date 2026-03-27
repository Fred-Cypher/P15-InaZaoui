<?php

namespace App\DataFixtures;

use App\Entity\Media;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

        public function load(ObjectManager $manager): void
    {
        // Création d'un Admin
        $admin = new User();
        $admin->setEmail('admin@test.com')
            ->setName('Ina Admin')
            ->setAdmin(true)
            ->setAuthorised(true)
            ->setPassword($this->hasher->hashPassword($admin, 'password'));
        $manager->persist($admin);

        // Création d'un invité actif
        $guestActive = new User();
        $guestActive->setEmail('active@test.com')
            ->setName('Camille Actif')
            ->setAdmin(false)
            ->setAuthorised(true)
            ->setPassword($this->hasher->hashPassword($guestActive, 'password'));
        $manager->persist($guestActive);

        // Création d'un invité bloqué
        $guestBlocked = new User();
        $guestBlocked->setEmail('blocked@test.com')
            ->setName('Claude Bloque')
            ->setAdmin(false)
            ->setAuthorised(false)
            ->setPassword($this->hasher->hashPassword($guestBlocked, 'password'));
        $manager->persist($guestBlocked);

        // Ajout de quelques médias pour l'invité actif
        $photos = ['cookie.jpg', 'lilas.jpg', 'mezenc.jpg', 'mouton.jpg', 'ourson.jpg'];
        foreach ($photos as $index => $fileName) {
            $media = new Media();
            $media->setTitle("Photo test " . ($index + 1))
                ->setPath("uploads/tests/" . $fileName)
                ->setUser($guestActive);
            $manager->persist($media);
        }

        $manager->flush();
    }
}
