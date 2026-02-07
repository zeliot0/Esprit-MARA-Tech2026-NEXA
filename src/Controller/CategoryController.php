<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('', name: 'category_index', methods: ['GET'])]
    public function index(Request $request, CategoryRepository $repo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ASSOCIATION');
        $q = trim((string) $request->query->get('q', ''));
        if ($q !== '') {
            $categories = $repo->createQueryBuilder('c')
                ->where('LOWER(c.name) LIKE :q OR LOWER(c.slug) LIKE :q')
                ->setParameter('q', '%' . mb_strtolower($q) . '%')
                ->orderBy('c.id', 'DESC')
                ->getQuery()->getResult();
        } else {
            $categories = $repo->findBy([], ['id' => 'DESC']);
        }

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'q' => $q,
        ]);
    }

    #[Route('/new', name: 'category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $cat = new Category();
        $form = $this->createForm(CategoryType::class, $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // slug auto إذا فاضي
            if (method_exists($cat, 'getSlug') && (!$cat->getSlug())) {
                $slugger = new AsciiSlugger();
                $cat->setSlug(mb_strtolower($slugger->slug((string) $cat->getName())->toString()));
            }

            $em->persist($cat);
            $em->flush();

            $this->addFlash('success', 'Catégorie créée ✅');
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'category_edit', methods: ['GET', 'POST'])]
    public function edit(Category $cat, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (method_exists($cat, 'getSlug') && (!$cat->getSlug())) {
                $slugger = new AsciiSlugger();
                $cat->setSlug(mb_strtolower($slugger->slug((string) $cat->getName())->toString()));
            }

            $em->flush();
            $this->addFlash('success', 'Catégorie mise à jour ✅');
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'cat' => $cat,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'category_delete', methods: ['POST'])]
    public function delete(Category $cat, Request $request, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('del_cat_' . $cat->getId(), (string) $request->request->get('_token'))) {
            $em->remove($cat);
            $em->flush();
            $this->addFlash('success', 'Catégorie supprimée ✅');
        }

        return $this->redirectToRoute('category_index');
    }
}
