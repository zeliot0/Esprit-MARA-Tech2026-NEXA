<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/blog')]
class BlogAdminController extends AbstractController
{
    #[Route('', name: 'admin_blog_index', methods: ['GET'])]
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        return $this->render('admin/blog/index.html.twig', [
            'posts' => $blogPostRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'admin_blog_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        if ($request->isMethod('POST')) {
            $post = new BlogPost();
            $post->setTitle($request->request->get('title'));
            $post->setContent($request->request->get('content'));
            $post->setSummary($request->request->get('summary'));
            $post->setSlug($slugger->slug($post->getTitle())->lower());
            $post->setIsPublished($request->request->get('isPublished') === 'on');
            $post->setAuthor($this->getUser());

            // Handle image upload if needed (simplified for now)
            $post->setImage($request->request->get('image_url'));

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Article créé avec succès.');
            return $this->redirectToRoute('admin_blog_index');
        }

        return $this->render('admin/blog/new.html.twig');
    }

    #[Route('/{id}/edit', name: 'admin_blog_edit', methods: ['GET', 'POST'])]
    public function edit(BlogPost $post, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        if ($request->isMethod('POST')) {
            $post->setTitle($request->request->get('title'));
            $post->setContent($request->request->get('content'));
            $post->setSummary($request->request->get('summary'));
            $post->setSlug($slugger->slug($post->getTitle())->lower());
            $post->setIsPublished($request->request->get('isPublished') === 'on');
            $post->setUpdatedAt(new \DateTimeImmutable());

            $post->setImage($request->request->get('image_url'));

            $em->flush();

            $this->addFlash('success', 'Article mis à jour.');
            return $this->redirectToRoute('admin_blog_index');
        }

        return $this->render('admin/blog/edit.html.twig', ['post' => $post]);
    }

    #[Route('/{id}/delete', name: 'admin_blog_delete', methods: ['POST'])]
    public function delete(BlogPost $post, EntityManagerInterface $em): Response
    {
        $em->remove($post);
        $em->flush();

        $this->addFlash('success', 'Article supprimé.');
        return $this->redirectToRoute('admin_blog_index');
    }
}
