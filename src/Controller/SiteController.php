<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Blog;
use App\Form\Type\BlogType;
use App\Service\ModifyBlogJSON;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $blogs = json_decode(file_get_contents("blogposts.json"), true);

        return $this->render('home/index.html.twig', ['blogs' => $blogs,]);
    }

    /**
     * @Route("/showblog/{id}", name="show", methods={"GET"})
     */
    public function showblog($id)
    {
        $blogs = json_decode(file_get_contents("blogposts.json"), true);
        foreach($blogs as $bloggy){
            if(intval($bloggy['id']) == $id){
                $blog = $bloggy;
            }
        }
        return $this->render('crud/show.html.twig', ['blog' => $blog,]);
    }

    /**
     * @Route("/editblog/{id}", name="edit", methods={"GET","POST"})
     */
    public function editblog($id, Request $request): Response
    {
        $blogs = json_decode(file_get_contents("blogposts.json"), true);
        foreach($blogs as $bloggy){
            if(intval($bloggy['id']) == $id){
                $blog_array = $bloggy;
            }
        }

        $blog = new Blog();
        $blog->setTitle($blog_array["title"]);
        $blog->setBody($blog_array["body"]);
        $blog->setRates($blog_array["rates"]);

        $form = $this->createForm(BlogType::class, $blog);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $blog_form_data = $form->getData();
            $blogs = json_decode(file_get_contents("blogposts.json"), true);

            foreach($blogs as $bloggy){
                if(intval($bloggy['id']) == $id){
                    $pos = intval($id)-1;

                    unset($blogs["$pos"]["title"]);
                    unset($blogs["$pos"]["body"]);
                    unset($blogs["$pos"]["rates"]);

                    $blogs["$pos"]["title"] = $blog_form_data->getTitle();
                    $blogs["$pos"]["body"] = $blog_form_data->getBody();
                    $blogs["$pos"]["rates"] = $blog_form_data->getRates();

                }
            }
            file_put_contents("blogposts.json",json_encode($blogs, JSON_PRETTY_PRINT|JSON_NUMERIC_CHECK));
            return $this->redirectToRoute('home');
        }
        return $this->render('crud/edit.html.twig', ['form'=> $form->createView(), 'blog' => $blog,]);
    }

    /**
     * @Route("/deleteblog/{id}", name="delete", methods={"GET"})
     */
    public function deleteblog($id): Response
    {
        $blogs = json_decode(file_get_contents("blogposts.json"), true);
        foreach($blogs as $bloggy){
            if(intval($bloggy['id']) == $id){
                unset($blogs[$id-1]);
            }
        }

        //id must be decremented
        $last_id_string = json_decode(file_get_contents('id_store.txt'), true);
        $last_id = intval($last_id_string);
        $last_id = $last_id - 1;

        //update storages
        file_put_contents("id_store.txt", $last_id);
        file_put_contents("blogposts.json",json_encode($blogs));

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/createblog", name="create")
     */
    public function createblog(Request $request, ModifyBlogJSON $modfiy_json): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $blog = $form->getData();

            //id must be incremented
            $last_id_string = json_decode(file_get_contents('id_store.txt'), true);
            $last_id = intval($last_id_string);
            $last_id = $last_id + 1;

            $new_blog_post = array('id'=>$last_id, 'title'=>$blog->getTitle(), 'body'=>$blog->getBody(), 'rates'=>$blog->getRates());
            file_put_contents("id_store.txt", $last_id);

            $blog_posts = json_decode(file_get_contents('blogposts.json'), true);
            if($blog_posts != false){
                array_push($blog_posts, $new_blog_post);
                file_put_contents("blogposts.json",json_encode($blog_posts));
            }
            else{
                $start_offset = "{\"0\":";
                file_put_contents("blogposts.json", $start_offset.json_encode($new_blog_post)."}");
            }
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