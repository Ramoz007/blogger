<?php

namespace App\Form\Type;

use App\Form\Type\BlogType;
use App\Entity\Blog;
use Symfony\Component\Form\Test\TypeTestCase;

class BlogTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'title' => 'title1',
            'body' => 'body 1',
            'rating' => 4,
        ];

        $model = new Blog();
        // $formData will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(BlogType::class, $model);

        $expected = new Blog();
        //populate the Blog object with $formData
        $expected->setTitle($formData['title']);
        $expected->setBody($formData['body']);
        $expected->setRating($formData['rating']);

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $formData was modified as expected when the form was submitted
        $this->assertEquals($expected, $model);
    }
}