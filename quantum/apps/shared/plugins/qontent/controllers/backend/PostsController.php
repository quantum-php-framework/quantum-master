<?php

namespace Qontent\Backend;

use Qubes\FormElementsFactory;

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
        qs('hi')->render();

        $this->setAutoRender(false);
    }

    public function new_post()
    {
        dd($this->user);

        $this->set('create_link', '/create/some/crap');
        $form = qform(new FormElementsFactory());
        $form->text('hi', 'title')
            ->textarea('body', 'body')->submitButton('send')->toOutput();

        //$this->setGenericFormView('waka');
        $this->output->setMainView('posts', 'new_post');
    }






}
