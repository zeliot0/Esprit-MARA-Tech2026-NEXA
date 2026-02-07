<?php

namespace App\Controller;

use App\Entity\CaseEntity;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    #[Route('/donate/{id}', name: 'app_donate', methods: ['POST'])]
    public function donate(CaseEntity $case, Request $request): Response
    {
        $stripeSecretKey = $this->getParameter('stripe_secret_key');
        if (!$stripeSecretKey || $stripeSecretKey === 'sk_test_***') {
            $this->addFlash('error', 'Configuration Stripe manquante. Veuillez ajouter votre STRIPE_SECRET_KEY dans le fichier .env.');
            return $this->redirectToRoute('case_entity_show', ['id' => $case->getId()]);
        }

        Stripe::setApiKey($stripeSecretKey);

        $amount = $request->request->get('amount');
        if (!$amount || $amount <= 0) {
            $this->addFlash('error', 'Veuillez entrer un montant valide.');
            return $this->redirectToRoute('case_entity_show', ['id' => $case->getId()]);
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Don pour : ' . $case->getTitle(),
                        ],
                        'unit_amount' => $amount * 100, // En centimes
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('app_payment_success', ['id' => $case->getId(), 'amount' => $amount], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('case_entity_show', ['id' => $case->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url, 303);
    }

    #[Route('/payment/success/{id}', name: 'app_payment_success')]
    public function success(CaseEntity $case, Request $request, EntityManagerInterface $em): Response
    {
        $amount = (float) $request->query->get('amount');

        // Update current amount
        $current = (float) $case->getCurrentAmount();
        $case->setCurrentAmount((string) ($current + $amount));

        $em->flush();

        $this->addFlash('success', 'Merci pour votre don généreux de ' . $amount . ' TND !');

        return $this->redirectToRoute('case_entity_show', ['id' => $case->getId()]);
    }
}
