<?php

namespace App\Controller\Admin;

use App\Entity\CaseEntity;
use App\Entity\Category;
use App\Entity\Donation;
use App\Entity\AuditLog;
use App\Entity\User;
use App\Repository\AuditLogRepository;
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

        $recentCases = $em->getRepository(CaseEntity::class)->findBy([], ['createdAt' => 'DESC'], 5);

        $urgencyStats = $em->getRepository(CaseEntity::class)->createQueryBuilder('c')
            ->select('c.urgency, COUNT(c.id) as count')
            ->groupBy('c.urgency')
            ->getQuery()
            ->getResult();

        return $this->render('admin/dashboard.html.twig', [
            'stats' => $stats,
            'recentCases' => $recentCases,
            'urgencyStats' => $urgencyStats,
            'userCounts' => [
                'admins' => $em->getRepository(User::class)->count(['role' => 'ROLE_ADMIN']),
                'associations' => $em->getRepository(User::class)->count(['role' => 'ROLE_ASSOCIATION']),
                'donors' => $em->getRepository(User::class)->count(['role' => 'ROLE_DONOR']),
            ]
        ]);
    }

    #[Route('/users', name: 'admin_user_index')]
    public function users(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(User::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/{id}/toggle-active', name: 'admin_user_toggle_active', methods: ['POST'])]
    public function toggleUserActive(User $user, EntityManagerInterface $em): Response
    {
        $user->setIsActive(!$user->isActive());
        $em->flush();

        $status = $user->isActive() ? 'activé' : 'banni';
        $this->addFlash('success', "L'utilisateur {$user->getFullName()} a été {$status}.");

        return $this->redirectToRoute('admin_user_index');
    }

    #[Route('/donations', name: 'admin_donation_index')]
    public function donations(EntityManagerInterface $em): Response
    {
        $donations = $em->getRepository(Donation::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/donations.html.twig', [
            'donations' => $donations,
        ]);
    }

    #[Route('/logs', name: 'admin_audit_logs')]
    public function logs(AuditLogRepository $repo): Response
    {
        // Assuming there's an AuditLog entity and repository
        $logs = $repo->findBy([], ['createdAt' => 'DESC'], 100);

        return $this->render('admin/audit_logs.html.twig', [
            'logs' => $logs,
        ]);
    }
}
