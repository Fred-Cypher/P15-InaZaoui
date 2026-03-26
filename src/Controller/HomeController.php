<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Media;
use App\Entity\User;
use App\Repository\AlbumRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }
    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('front/home.html.twig');
    }

    #[Route("/guests", name: "guests")]
    public function guests(): Response
    {
        $guests = $this->em->getRepository(User::class)->findBy([
            'admin' => false,
            'authorised' => true
            ]);
        return $this->render('front/guests.html.twig', [
            'guests' => $guests
        ]);
    }

    #[Route("/guest/{id}", name: "guest")]
    public function guest(int $id): Response
    {
        $guest = $this->em->getRepository(User::class)->findOneBy([
            'id' => $id,
            'authorised' => true
        ]);

        if (!$guest) {
            throw $this->createNotFoundException("Cet invité n'existe pas ou n'est pas accessible");
        }

        return $this->render('front/guest.html.twig', [
            'guest' => $guest
        ]);
    }

    #[Route("/portfolio/{id}", name: "portfolio")]
    public function portfolio(AlbumRepository $albumRepository, UserRepository $userRepository, MediaRepository $mediaRepository, ?int $id = null): Response
    {
        $albums =$albumRepository->findAll();
        $album = $id ? $albumRepository->find($id) : null;
        $user = $userRepository->findOneBy(['admin' => true]);

        $medias = $album
            ? $mediaRepository->findBy(['album' => $album])
            : $mediaRepository->findBy(['user' => $user]);
        return $this->render('front/portfolio.html.twig', [
            'albums' => $albums,
            'album' => $album,
            'medias' => $medias
        ]);
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('front/about.html.twig');
    }
}
