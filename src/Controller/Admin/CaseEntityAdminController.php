<?php

namespace App\Controller\Admin;

use App\Entity\CaseEntity;
use App\Entity\User;
use App\Form\CaseEntityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/case')]
class CaseEntityAdminController extends AbstractController
{
    #[Route('', name: 'admin_case_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('admin/case/index.html.twig', [
            'cases' => $em->getRepository(CaseEntity::class)->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'admin_case_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $case = new CaseEntity();
        $form = $this->createForm(CaseEntityType::class, $case);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$case->getCreatedBy()) {
                $user = $this->getUser();
                if (!$user instanceof User) {
                    $user = $em->getRepository(User::class)->findOneBy([], ['id' => 'ASC']);
                }
                $case->setCreatedBy($user);
            }

            $em->persist($case);
            $em->flush();

            $this->addFlash('success', 'Appel d\'offre créé.');
            return $this->redirectToRoute('admin_case_index');
        }

        return $this->render('admin/case/new.html.twig', [
            'case' => $case,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_case_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CaseEntity $case, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CaseEntityType::class, $case);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $case->setUpdatedAt(new \DateTime());
            $em->flush();
            $this->addFlash('success', 'Appel d\'offre mis à jour.');
            return $this->redirectToRoute('admin_case_index');
        }

        return $this->render('admin/case/edit.html.twig', [
            'case' => $case,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_case_delete', methods: ['POST'])]
    public function delete(Request $request, CaseEntity $case, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $case->getId(), $request->request->get('_token'))) {
            $em->remove($case);
            $em->flush();
            $this->addFlash('success', 'Appel d\'offre supprimé.');
        }

        return $this->redirectToRoute('admin_case_index');
    }
}
