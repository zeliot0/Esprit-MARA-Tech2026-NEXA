<?php

namespace App\Controller;

use App\Entity\CaseEntity;
use App\Entity\CaseUpdate;
use App\Entity\Comment;
use App\Entity\BlogPost;
use App\Entity\BlogComment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InteractionController extends AbstractController
{
    #[Route('/case/{id}/add-update', name: 'app_case_add_update', methods: ['POST'])]
    public function addUpdate(CaseEntity $case, Request $request, EntityManagerInterface $em): Response
    {
        // ... (existing code for case updates)
        if ($this->getUser() !== $case->getCreatedBy() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas ajouter de mise à jour à cet appel.');
        }

        $content = $request->request->get('content');
        $imageUrl = $request->request->get('image_url');

        if ($content) {
            $update = new CaseUpdate();
            $update->setCase($case);
            $update->setContent($content);
            $update->setImage($imageUrl);

            $em->persist($update);
            $em->flush();

            $this->addFlash('success', 'Votre mise à jour a été publiée avec succès !');
        }

        return $this->redirectToRoute('case_entity_show', ['id' => $case->getId()]);
    }

    #[Route('/case/{id}/add-comment', name: 'app_case_add_comment', methods: ['POST'])]
    public function addComment(CaseEntity $case, Request $request, EntityManagerInterface $em): Response
    {
        // ... (existing code for case comments)
        if (!$this->getUser()) {
            $this->addFlash('error', 'Vous devez être connecté pour laisser un commentaire.');
            return $this->redirectToRoute('app_login');
        }

        $content = $request->request->get('content');

        if ($content) {
            $comment = new Comment();
            $comment->setCase($case);
            $comment->setAuthor($this->getUser());
            $comment->setContent($content);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Merci pour votre message !');
        }

        return $this->redirectToRoute('case_entity_show', ['id' => $case->getId()]);
    }

    #[Route('/blog/post/{id}/add-comment', name: 'app_blog_add_comment', methods: ['POST'])]
    public function addBlogComment(BlogPost $post, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('error', 'Vous devez être connecté pour laisser un message sur le journal.');
            return $this->redirectToRoute('app_login');
        }

        $content = $request->request->get('content');

        if ($content) {
            $comment = new BlogComment();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());
            $comment->setContent($content);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Votre message a été ajouté au journal Aychek !');
        }

        return $this->redirectToRoute('app_blog_show', ['slug' => $post->getSlug()]);
    }
}
