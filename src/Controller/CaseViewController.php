<?php

namespace App\Controller;

use App\Entity\CaseView;
use App\Form\CaseViewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/case/view')]
final class CaseViewController extends AbstractController
{
    #[Route(name: 'app_case_view_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $caseViews = $entityManager
            ->getRepository(CaseView::class)
            ->findAll();

        return $this->render('case_view/index.html.twig', [
            'case_views' => $caseViews,
        ]);
    }

    #[Route('/new', name: 'app_case_view_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $caseView = new CaseView();
        $form = $this->createForm(CaseViewType::class, $caseView);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($caseView);
            $entityManager->flush();

            return $this->redirectToRoute('app_case_view_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('case_view/new.html.twig', [
            'case_view' => $caseView,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_case_view_show', methods: ['GET'])]
    public function show(CaseView $caseView): Response
    {
        return $this->render('case_view/show.html.twig', [
            'case_view' => $caseView,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_case_view_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CaseView $caseView, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CaseViewType::class, $caseView);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_case_view_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('case_view/edit.html.twig', [
            'case_view' => $caseView,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_case_view_delete', methods: ['POST'])]
    public function delete(Request $request, CaseView $caseView, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$caseView->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($caseView);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_case_view_index', [], Response::HTTP_SEE_OTHER);
    }
}
