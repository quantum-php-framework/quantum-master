<?php

namespace Qontent\Backend;

class SettingsController extends \Quantum\Controller
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

        $this->output->setMainView('settings', 'index');

    }








}
