<?php

namespace App\Controller;

use App\Entity\CaseImage;
use App\Form\CaseImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/case/image')]
final class CaseImageController extends AbstractController
{
    #[Route(name: 'app_case_image_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $caseImages = $entityManager
            ->getRepository(CaseImage::class)
            ->findAll();

        return $this->render('case_image/index.html.twig', [
            'case_images' => $caseImages,
        ]);
    }

    #[Route('/new', name: 'app_case_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $caseImage = new CaseImage();
        $form = $this->createForm(CaseImageType::class, $caseImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($caseImage);
            $entityManager->flush();

            return $this->redirectToRoute('app_case_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('case_image/new.html.twig', [
            'case_image' => $caseImage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_case_image_show', methods: ['GET'])]
    public function show(CaseImage $caseImage): Response
    {
        return $this->render('case_image/show.html.twig', [
            'case_image' => $caseImage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_case_image_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CaseImage $caseImage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CaseImageType::class, $caseImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_case_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('case_image/edit.html.twig', [
            'case_image' => $caseImage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_case_image_delete', methods: ['POST'])]
    public function delete(Request $request, CaseImage $caseImage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$caseImage->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($caseImage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_case_image_index', [], Response::HTTP_SEE_OTHER);
    }
}
