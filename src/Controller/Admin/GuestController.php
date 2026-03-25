<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
class GuestController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route('/admin/guest', name: "admin_guest_index")]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        $criteria = [];

        $users = $this->em->getRepository(User::class)->findBy(
            $criteria,
            ['id' => 'ASC'],
            20,
            20 * ($page -1)
        );
        $total = $this->em->getRepository(User::class)->count($criteria);

        return $this->render('admin/guest/index.html.twig', [
            'users' => $users,
            'total' => $total,
            'page' => $page
        ]);
    }
}
