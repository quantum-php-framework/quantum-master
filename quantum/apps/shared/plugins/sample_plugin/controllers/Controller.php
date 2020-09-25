<?php

namespace SampleCompany\SamplePlugin;

class Controller extends \Quantum\Controller
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

        //return new \Quantum\Psr7\Response\HtmlResponse('hi');

        //return  Quantum\Psr7\ResponseFactory::createResponseWithContents('hello man')->withHeader("pepe", "100");

        //return \Quantum\Psr7\ResponseFactory::json(["hola" => 1]);

        //return json_encode(["hola" => 1]);

        //Quantum\Session::start();

        //QM::cookies()->get('pepe');


        //$this->output->setHeaderParam('pepe', '100');

        //$cookie = new \Quantum\Psr7\ResponseCookie('cas', true);


        //dd(\QM::cookies()->getDecrypted('cas'));
        //echo "hidfdfdfdf";
        //return response('hihihi')->withExpiredCookie('pepe');

        //return "http://google.com";
        //return redirect("http://google.com");

        //$this->setTemplate('default');

        //$response = new \Quantum\Psr7\Response\RedirectResponse('http://google.com');

        //$response = new \Quantum\Psr7\Response\JsonResponse(['var' => 100]);

        //$response = new \Quantum\Psr7\Response\HtmlResponse('hi');

        //$response = new \Quantum\Psr7\Response\EmptyResponse();
        //return $response;

        //return ['hi' => true];

        //qs('Welcome to Quantum')->render();
    }


    public function index2()
    {

    }






}
