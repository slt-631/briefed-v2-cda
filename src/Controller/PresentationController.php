<?php

namespace App\Controller;

use App\Entity\Presentation;
use App\Entity\User;
use App\Form\PresentationType;
use App\Repository\PresentationRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/presentations')]
#[IsGranted('ROLE_USER')]
class PresentationController extends AbstractController
{
    #[Route('', name: 'app_presentation_index', methods: ['GET'])]
    public function index(PresentationRepository $repository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $presentations = $repository->findByOwner($user);

        return $this->render('presentation/index.html.twig', [
            'presentations' => $presentations,
        ]);


    }

    #[Route('/new', name: 'app_presentation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, FileUploader $uploader): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $presentation = new Presentation();
        $presentation->setOwner($user);

        if ($presentation->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleImageUpload($uploader, $form->get('imageFile')->getData(), $presentation);
            $this->handleBackgroundImageUpload($uploader, $form->get('background')->get('backgroundImageFile')->getData(), $presentation);
            $em->persist($presentation);
            $em->flush();

            return $this->redirectToRoute('app_presentation_index');
        }

        return $this->render('presentation/new.html.twig', [
            'form' => $form,
            'presentation' => $presentation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_presentation_edit', methods: ['GET', 'POST'])]
    public function edit(Presentation $presentation, Request $request, EntityManagerInterface $em, FileUploader $uploader): Response
    {
        if ($presentation->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleImageUpload($uploader, $form->get('imageFile')->getData(), $presentation);
            $this->handleBackgroundImageUpload($uploader, $form->get('background')->get('backgroundImageFile')->getData(), $presentation);
            $presentation->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($presentation->getBackground());
            $em->flush();

            return $this->redirectToRoute('app_presentation_index');
        }

        return $this->render('presentation/edit.html.twig', [
            'form' => $form,
            'presentation' => $presentation,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_presentation_delete', methods: ['POST'])]
    public function delete(Presentation $presentation, Request $request, EntityManagerInterface $em, FileUploader $uploader): Response
    {
        if ($presentation->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $presentation->getId(), $request->getPayload()->getString('_token'))) {
            $filename = $presentation->getImageFilename();

            $em->remove($presentation);
            $em->flush();

            if ($filename) {
                $uploader->remove($filename);
            }
        }

        return $this->redirectToRoute('app_presentation_index');
    }

    private function handleImageUpload(FileUploader $uploader, ?UploadedFile $imageFile, Presentation $presentation): void
    {
        if (!$imageFile) {
            return;
        }

        $oldFilename = $presentation->getImageFilename();

        $presentation->setImageFilename($uploader->upload($imageFile));

        if ($oldFilename) {
            $uploader->remove($oldFilename);
        }
    }

    private function handleBackgroundImageUpload(FileUploader $uploader, ?UploadedFile $imageFile, Presentation $presentation): void
    {
        if (!$imageFile) {
            return;
        }

        $oldFilename = $presentation->getBackground()->getImageFilename();

            $presentation->getBackground()->setImageFilename($uploader->upload($imageFile));

        if ($oldFilename) {
            $uploader->remove($oldFilename);
        }
    }
}
