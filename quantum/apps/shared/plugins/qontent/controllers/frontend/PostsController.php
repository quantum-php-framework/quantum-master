<?php

namespace Qontent\Frontend;

use Qontent\Entities\Post;

class PostsController extends \Quantum\Controller
{

    /**
     * Create a controller, no dependency injection has happened.
     */
    function __construct()
    {

    }

    /**
     * Called after dependency injection, all environment variables are ready.
     */
    protected function __post_construct()
    {

    }

    /**
     * Called before calling the main controller action, all environment variables are ready.
     */
    protected function __pre_dispatch()
    {

    }

    /**
     * Called after calling the main controller action, all vars set by controller are ready.
     */
    protected function __post_dispatch()
    {

    }

    /**
     * Called after calling the main controller action, before calling Quantum\Output::render
     */
    protected function __pre_render()
    {

    }

    /**
     * Called after calling Quantum\Output::render
     */
    protected function __post_render()
    {

    }


    /**
     * Public: index
     */
    public function index()
    {
        qs('Welcome to Qontent')->render();

        $this->setAutoRender(false);
    }


    public function post()
    {
        //\Qontent\Entities\Post::truncate();


        //\Quantum\Doctrine::truncate('Qontent\Entities\Post');

        $results = \Quantum\Doctrine::executeQuery("SELECT p FROM Qontent\Entities\Post p");
        dd($results);


        //$manager = \Quantum\Doctrine\Doctrine::getEntityManager();

        //$post = $manager->getRepository('\Qontent\Entities\Post')->findOneBy(['id' => 1]);

        $post = Post::findById(20);
        $post->setContent('hidfdfdfddf'.quuid());
        $post->save();



        //$post->truncate();

        //dd($post);

        //dd($results);

        $this->setAutoRender(false);
    }


}
