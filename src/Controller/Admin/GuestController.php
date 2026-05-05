<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
class GuestController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
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

    #[Route('/admin/guest/toggle/{id}', name: "admin_guest_toggle", methods: ['POST'])]
    public function toggle(#[MapEntity(id: 'id')] User $user): JsonResponse
    {
        $user->setAuthorised(!$user->isAuthorised());
        $this->em->flush();

        return new JsonResponse([
            'success' => true,
            'isAuthorised' => $user->isAuthorised()
        ]);
    }

    #[Route('/admin/guest/delete/{id}', name: "admin_guest_delete")]
    public function delete(Request $request, #[MapEntity(id: 'id')] User $user): Response
    {
        if (!$this->isCSRFTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Jeton de sécurité invalide.');
            return $this->redirectToRoute('admin_guest_index');
        }

        $filePaths = [];
        foreach ($user->getMedias() as $media){
            $filePaths[] = $this->getParameter('kernel.project_dir') . '/public/' . $media->getPath();
        }

        $this->em->remove($user);
        $this->em->flush();

        foreach ($filePaths as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->addFlash('success', 'L\'invité et tous ses médias ont bien été supprimés');

        return $this->redirectToRoute('admin_guest_index');
    }
}
