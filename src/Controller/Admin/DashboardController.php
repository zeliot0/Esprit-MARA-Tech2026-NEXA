<?php

namespace App\Controller\Admin;

use App\Entity\CaseEntity;
use App\Entity\Category;
use App\Entity\Donation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'admin_dashboard')]
    #[Route('/', name: 'admin_dashboard_slash')]
    public function index(EntityManagerInterface $em): Response
    {
        $stats = [
            'total_cases' => $em->getRepository(CaseEntity::class)->count([]),
            'total_users' => $em->getRepository(User::class)->count([]),
            'total_donations' => $em->getRepository(Donation::class)->count([]),
            'total_categories' => $em->getRepository(Category::class)->count([]),
            'raised_amount' => $em->getRepository(CaseEntity::class)->createQueryBuilder('c')
                ->select('SUM(c.currentAmount)')
                ->getQuery()
                ->getSingleScalarResult() ?? 0,
        ];

        // Recent cases
        $recentCases = $em->getRepository(CaseEntity::class)->findBy([], ['createdAt' => 'DESC'], 5);

        // Distribution by urgency for a chart
        $urgencyStats = $em->getRepository(CaseEntity::class)->createQueryBuilder('c')
            ->select('c.urgency, COUNT(c.id) as count')
            ->groupBy('c.urgency')
            ->getQuery()
            ->getResult();

        return $this->render('admin/dashboard.html.twig', [
            'stats' => $stats,
            'recentCases' => $recentCases,
            'urgencyStats' => $urgencyStats,
        ]);
    }
}
