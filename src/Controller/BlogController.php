<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/blog')]
class BlogController extends AbstractController
{
    #[Route('', name: 'app_blog_index', methods: ['GET'])]
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'posts' => $blogPostRepository->findLatestPublished(12),
        ]);
    }

    #[Route('/new-association', name: 'app_blog_create_association', methods: ['POST'])]
    #[IsGranted('ROLE_ASSOCIATION')]
    public function createAssociation(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $post = new BlogPost();
        $post->setTitle($request->request->get('title'));
        $post->setContent($request->request->get('content'));
        $post->setSummary($request->request->get('summary'));
        $post->setSlug($slugger->slug($post->getTitle())->lower() . '-' . uniqid());
        $post->setIsPublished(true); // Associations publish immediately by default for now
        $post->setAuthor($this->getUser());
        $post->setImage($request->request->get('image_url') ?:
            'https://images.unsplash.com/photo-1497215728101-856f4ea02164?q=80&w=800');

        $em->persist($post);
        $em->flush();

        $this->addFlash('success', 'Votre article a été publié sur le journal Aychek !');
        return $this->redirectToRoute('app_blog_index');
    }

    #[Route('/view/{slug}', name: 'app_blog_show', methods: ['GET'])]
    public function show(string $slug, BlogPostRepository $blogPostRepository): Response
    {
        $post = $blogPostRepository->findOneBy(['slug' => $slug, 'isPublished' => true]);

        if (!$post) {
            throw $this->createNotFoundException('L\'article demandé n\'existe pas.');
        }

        return $this->render('blog/show.html.twig', [
            'post' => $post,
        ]);
    }
}
