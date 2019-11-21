<?php

/*
 * class IndexController
 */

class IndexController extends Quantum\Controller
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

        qs('Welcome to Quantum')->render();


      
    }

    public function test()
    {
        $this->setTemplate('cms');

        $this->set('var', 'hello sparx');

        $fib = Quantum\Math\Sequence\Advanced::fibonacci(360);

        pre($fib);


       // $this->setRenderFullTemplate(false);
    }


    public function some_other_method()
    {
        Quantum\ApiException::iAmATeapot();
    }
    
        
        
     
    
}

?>