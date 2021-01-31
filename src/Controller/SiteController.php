<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Form\Type\BlogType;
use App\Service\ModifyBlogJSON;

use App\Entity\Blog;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $blogs = $entityManager->getRepository(Blog::class)->findAll();

        return $this->render('home/index.html.twig', ['blogs' => $blogs,]);
    }

    /**
     * @Route("/showblog/{id}", name="show", methods={"GET"})
     */
    public function showblog($id)
    {
        $blog = $this->getDoctrine()
        ->getRepository(Blog::class)
        ->find($id);

        return $this->render('crud/show.html.twig', ['blog' => $blog,]);
    }

    /**
     * @Route("/editblog/{id}", name="edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function editblog($id, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $blog = $entityManager->getRepository(Blog::class)->find($id);

        if (!$blog) {
            throw $this->createNotFoundException(
                'No blog found for id '.$id
            );
        }

        $form = $this->createForm(BlogType::class, $blog);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $blog = $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('crud/edit.html.twig', ['form'=> $form->createView(), 'blog' => $blog,]);
    }

    /**
     * @Route("/deleteblog/{id}", name="delete", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteblog($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $blog = $entityManager->getRepository(Blog::class)->find($id);
        $entityManager->remove($blog);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/createblog", name="create")
     * @IsGranted("ROLE_USER")
     */
    public function createblog(Request $request, ModifyBlogJSON $modfiy_json): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $blog = new Blog();

        $form = $this->createForm(BlogType::class, $blog);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $blog = $form->getData();
            
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('crud/create.html.twig', ['form'=> $form->createView(),]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }
}