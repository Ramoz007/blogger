<?php
namespace App\Service;
use App\Entity\BlogPost;

class ModifyBlogJSON
{
    public function updateBlogJSON(Blog $blog_form_data, $id)
    {

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
    }
 
}