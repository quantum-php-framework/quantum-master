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
        $this->setTemplate('default');

        $validator = new Quantum\RequestParamValidator();

        $validator->rules([
            'url' => 'required|string|url',
            'description' => 'required|string',
        ]);

        if ($this->request->isPost())
        {
            if($validator->validatePost())
            {
                pre('success');
            }
            else
            {
                //var_dump($validator->getErrors());
                var_dump($validator->getErrorsFor('url'));
                //var_dump($validator->getAllErrorMessages());
                //var_dump($validator->getLastErrorMessages());

                //var_dump($validator->getErrorsValueTree());
                //var_dump($validator->getResult());
                //var_dump($validator->getErrorsCount());
                exit();
            }
        }


        $form = qform (new GenericFormElementsFactory());
        $form->text('Title', 'title');
        $form->text('Description', 'description');
        $form->submitButton('send')->toOutput();

    }


    public function method()
    {
        //qs('hi')->render();
        //Quantum\ApiException::iAmATeapot();
        //phpinfo();

        //$cache = Quantum\Cache\ServiceProvider::getInstance();

        //$cache->initRedis();

        //$cache->flush();

        $someVar = 'var';

        //Quantum\Cache::useMemcache();
        //Quantum\Cache::useRedis();

        Quantum\Cache::set($someVar, 'xxx');

        var_dump(Quantum\Cache::get($someVar));

        Quantum\Cache::delete($someVar);

        var_dump(Quantum\Cache::get($someVar));

        //$cache->initMemcache();

        //$cache->flush();

        Quantum\Cache::set($someVar, 'xxx');

        var_dump(Quantum\Cache::get($someVar));

        Quantum\Cache::increment('counterx', 1);

        var_dump(Quantum\Cache::get('counterx'));



        var_dump(Quantum\Cache::setDeferred('countera', function()
        {
            return 10;
        }));

        var_dump(Quantum\Cache::get('counterrr', function()
        {
            return 10;
        }));


        var_dump(Quantum\Cache::decrementWithCallback('counter-autoinc-c', function()
        {
            return 10;
        }));

        var_dump(Quantum\Cache::setWithCallback('counter-autoinc-d', function()
        {
            return 100;
        }));



        //$cache->setDriver('redis');

        //$cache->set('xxx', 343);

        //var_dump($cache->get('xxx'));

        //var_dump($cache->has('xxxa'));

        //$cache->flush();

        //var_dump($cache->has('xxx'));

        //$cache->getDelayed('xxx');



        //var_dump($cache->fetchAll());

    }
    
        
        
     
    
}

?>