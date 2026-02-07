<?php

namespace App\Controller;

use App\Entity\CaseEntity;
use App\Entity\Category;
use App\Entity\User;
use App\Form\CaseEntityType;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/case/entity')]
final class CaseEntityController extends AbstractController
{
    #[Route('/case', name: 'case_entity_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $em,
        CategoryRepository $catRepo
    ): Response {
        // ✅ repo عام (بدون repository مخصص)
        $caseRepo = $em->getRepository(CaseEntity::class);

        // ----------------------------------------
        // 1) Quick Add Category
        // ----------------------------------------
        $category = new Category();
        $catForm = $this->createForm(CategoryType::class, $category, [
            'attr' => ['id' => 'catQuickForm']
        ]);
        $catForm->handleRequest($request);

        // ----------------------------------------
        // 2) Create Case (same page)
        // ----------------------------------------
        $case = new CaseEntity();

        // preselect category via ?category=ID
        $selectedCategoryId = $request->query->get('category');
        if ($selectedCategoryId && ctype_digit((string) $selectedCategoryId) && method_exists($case, 'setCategory')) {
            $selected = $catRepo->find((int) $selectedCategoryId);
            if ($selected) {
                $case->setCategory($selected);
            }
        }

        $caseForm = $this->createForm(CaseEntityType::class, $case, [
            'attr' => ['id' => 'caseForm']
        ]);
        $caseForm->handleRequest($request);

        // ✅ distinguish which form submitted (باش ما يتخلطوش)
        $submittedCat = $request->isMethod('POST') && $request->request->has($catForm->getName());
        $submittedCase = $request->isMethod('POST') && $request->request->has($caseForm->getName());

        // ========================================
        // SAVE CATEGORY ✅ (createdAt typed safe)
        // ========================================
        if ($submittedCat && $catForm->isSubmitted() && $catForm->isValid()) {

            // slug auto (اختياري)
            if (method_exists($category, 'getSlug') && method_exists($category, 'setSlug') && !$category->getSlug()) {
                $slugger = new AsciiSlugger();
                $category->setSlug(mb_strtolower($slugger->slug((string) $category->getName())->toString()));
            }

            // ✅ IMPORTANT: ما نعملوش getCreatedAt() نهائيًا
            if (method_exists($category, 'setCreatedAt')) {
                $category->setCreatedAt(new \DateTime());
            }
            if (method_exists($category, 'setUpdatedAt')) {
                $category->setUpdatedAt(new \DateTime());
            }

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Catégorie ajoutée ✅');
            return $this->redirectToRoute('case_entity_index', [
                'category' => method_exists($category, 'getId') ? $category->getId() : null
            ]);
        }

        // ========================================
        // SAVE CASE ✅ FIX created_by_id NOT NULL
        // ========================================
        if ($submittedCase && $caseForm->isSubmitted() && $caseForm->isValid()) {

            // timestamps mandatory
            if (method_exists($case, 'setCreatedAt')) {
                $case->setCreatedAt(new \DateTime());
            }
            if (method_exists($case, 'setUpdatedAt')) {
                $case->setUpdatedAt(new \DateTime());
            }

            // defaults (باش ما يطيحش null)
            if (method_exists($case, 'setViewsCount')) {
                $case->setViewsCount(0);
            }
            if (method_exists($case, 'setCurrentAmount')) {
                // ما نستعملوش getter إذا ينجم يكون typed مش initialized
                $case->setCurrentAmount(0);
            }
            if (method_exists($case, 'setIsFeatured')) {
                // إذا ما تعمّرش، نخليه false
                $case->setIsFeatured($case->isFeatured() ?? false);
            }

            // ✅ created_by_id NOT NULL -> لازم يتعمر
            if (method_exists($case, 'setCreatedBy')) {
                $user = $this->getUser();

                if ($user instanceof User) {
                    $case->setCreatedBy($user);
                } else {
                    // fallback: أول user في DB
                    $fallbackUser = $em->getRepository(User::class)->findOneBy([], ['id' => 'ASC']);
                    if (!$fallbackUser) {
                        $this->addFlash('error', '❌ Table users فارغة. لازم تعمل User واحد باش created_by_id obligatoire.');
                        return $this->redirectToRoute('case_entity_index', [
                            'category' => $selectedCategoryId
                        ]);
                    }
                    $case->setCreatedBy($fallbackUser);
                }
            }

            $em->persist($case);
            $em->flush();

            $this->addFlash('success', 'Case créée ✅');
            return $this->redirectToRoute('case_entity_index', [
                'category' => $selectedCategoryId
            ]);
        }

        // ----------------------------------------
        // Lists in same page
        // ----------------------------------------
        $categories = $catRepo->findBy([], ['id' => 'DESC']);
        $cases = $caseRepo->findBy([], ['id' => 'DESC']);

        return $this->render('case_entity/index.html.twig', [
            'categories' => $categories,
            'cases' => $cases,
            'selectedCategoryId' => $selectedCategoryId,
            'catForm' => $catForm->createView(),
            'caseForm' => $caseForm->createView(),
        ]);
    }

    #[Route('/consult', name: 'case_entity_consult', methods: ['GET'])]
public function consult(
    Request $request,
    EntityManagerInterface $em,
    CategoryRepository $catRepo
): Response {
    $qb = $em->getRepository(CaseEntity::class)->createQueryBuilder('c');

    // =========================
    // FILTER: Category
    // =========================
    $categoryId = $request->query->get('category');
    if ($categoryId && ctype_digit((string)$categoryId)) {
        $qb->andWhere('c.category = :cat')
           ->setParameter('cat', (int)$categoryId);
    }

    // =========================
    // SEARCH: keyword
    // =========================
    $keyword = $request->query->get('q');
    if ($keyword) {
        $qb->andWhere('c.title LIKE :kw OR c.description LIKE :kw')
           ->setParameter('kw', '%' . $keyword . '%');
    }

    // =========================
    // SORT
    // =========================
    $sort = $request->query->get('sort');

    if ($sort === 'urgency') {
        // HIGH > MEDIUM > LOW
        $qb->addOrderBy(
            "CASE 
                WHEN c.urgency = 'HIGH' THEN 1
                WHEN c.urgency = 'MEDIUM' THEN 2
                ELSE 3
             END",
            'ASC'
        );
    } else {
        // default: latest first
        $qb->orderBy('c.createdAt', 'DESC');
    }

    $cases = $qb->getQuery()->getResult();
    $categories = $catRepo->findAll();

    return $this->render('case_entity/list.html.twig', [
        'cases' => $cases,
        'categories' => $categories,
        'selectedCategory' => $categoryId,
        'search' => $keyword,
        'sort' => $sort,
    ]);
}

   #[Route('/{id}', name: 'case_entity_show', methods: ['GET'])]
public function show(CaseEntity $case, EntityManagerInterface $em): Response
{
    // optional: نزيدو view count
    if (method_exists($case, 'setViewsCount')) {
        $case->setViewsCount(($case->getViewsCount() ?? 0) + 1);
        $em->flush();
    }

    return $this->render('case_entity/show.html.twig', [
        'case' => $case,
    ]);
}


}