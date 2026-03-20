<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
class GuestController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {}

    #[Route('/admin/guest', name: "admin_guest_index")]
    public function index(): Response
    {
        return $this->render('admin/guest/index.html.twig', []);
    }
}
