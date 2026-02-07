<?php

namespace App\Controller;

use App\Entity\CaseEntity;
use App\Repository\CaseEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/association')]
#[IsGranted('ROLE_ASSOCIATION')]
class AssociationDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'association_dashboard')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $repo = $em->getRepository(CaseEntity::class);

        // Stats limited to the logged-in association
        $cases = $repo->findBy(['createdBy' => $user], ['createdAt' => 'DESC']);

        $totalCollected = 0;
        $totalTarget = 0;
        foreach ($cases as $case) {
            $totalCollected += (float) $case->getCurrentAmount();
            $totalTarget += (float) $case->getTargetAmount();
        }

        $activeCases = count(array_filter($cases, fn($c) => $c->getStatus() === 'PUBLISHED'));

        // Data for charts
        $categoriesData = [];
        foreach ($cases as $case) {
            $catName = $case->getCategory()->getName();
            $categoriesData[$catName] = ($categoriesData[$catName] ?? 0) + 1;
        }

        // Event Stats
        $eventRepo = $em->getRepository(\App\Entity\Event::class);
        $events = $eventRepo->findBy(['createdBy' => $user]);
        $totalEvents = count($events);
        $upcomingEvents = count(array_filter($events, fn($e) => $e->getStartAt() > new \DateTime()));
        $completedEvents = count(array_filter($events, fn($e) => $e->getStatus() === 'COMPLETED'));

        return $this->render('association/dashboard.html.twig', [
            'totalCases' => count($cases),
            'totalCollected' => $totalCollected,
            'totalTarget' => $totalTarget,
            'activeCases' => $activeCases,
            'recentCases' => array_slice($cases, 0, 5),
            'categoriesLabels' => array_keys($categoriesData),
            'categoriesValues' => array_values($categoriesData),
            'totalEvents' => $totalEvents,
            'upcomingEvents' => $upcomingEvents,
            'completedEvents' => $completedEvents,
        ]);
    }
}
