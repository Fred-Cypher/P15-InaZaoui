<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Form\MediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MediaController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }
    #[Route("/admin/media", name: "admin_media_index")]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        $criteria = [];

        if (!$this->isGranted('ROLE_ADMIN')) {
            $criteria['user'] = $this->getUser();
        }

        $medias = $this->em->getRepository(Media::class)->findBy(
            $criteria,
            ['id' => 'ASC'],
            25,
            25 * ($page - 1)
        );
        $total = $this->em->getRepository(Media::class)->count([]);

        return $this->render('admin/media/index.html.twig', [
            'medias' => $medias,
            'total' => $total,
            'page' => $page
        ]);
    }

    #[Route("/admin/media/add", name: "admin_media_add")]
    public function add(
        Request $request,
            ): Response
    {
        $media = new Media();
        $form = $this->createForm(MediaType::class, $media, ['is_admin' => $this->isGranted('ROLE_ADMIN')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($file) {
                if (!$this->isGranted('ROLE_ADMIN')) {
                    $media->setUser($this->getUser());
                }

                $newFileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('kernel.project_dir') . '/public/uploads', $newFileName);
                $media->setPath('uploads/' . $newFileName);

                $this->em->persist($media);
                $this->em->flush();

                $this->addFlash('success', 'L\'image a bien été ajoutée');
                return $this->redirectToRoute('admin_media_index');
            } else {
                $this->addFlash('danger', 'Le fichier image est obligatoire');
            }
        }
        return $this->render('admin/media/add.html.twig', ['form' => $form->createView()]);
    }

    #[Route("/admin/media/delete/{id}", name: "admin_media_delete")]
    public function delete(int $id): Response
    {
        $media = $this->em->getRepository(Media::class)->find($id);
        $this->em->remove($media);
        $this->em->flush();
        unlink($media->getPath());

        return $this->redirectToRoute('admin_media_index');
    }
}
