<?

require_once (__DIR__ . "/system/modules/kernel/Kernel.php");

/**
 * @property  query_id
 */
class Quantum
{


    /**
     * @var \Quantum\Kernel
     */
    public $kernel;
    /**
     * @var \Quantum\Config
     */
    public $config;
    /**
     * @var
     */
    public $controller;
    /**
     * @var
     */
    public $task;
    /**
     * @var
     */
    public $object_id;
    /**
     * @var
     */
    public $database;
    /**
     * @var
     */
    public $state;
    /**
     * @var
     */
    public $csrf;
    /**
     * @var
     */
    public $activeController;
    /**
     * @var
     */
    public $root_folder;
    /**
     * @var
     */
    public $quantum_root;
    /**
     * @var
     */
    public $app_root;
    /**
     * @var
     */
    public $views_root;
    /**
     * @var
     */
    public $controllers_root;
    /**
     * @var
     */
    public $models_root;
    /**
     * @var
     */
    public $filters_root;
    /**
     * @var
     */
    public $helpers_root;
    /**
     * @var
     */
    public $templates_root;
    /**
     * @var
     */
    public $system_root;
    /**
     * @var
     */
    public $system_kernel_root;
    /**
     * @var
     */
    public $system_helpers_root;
    /**
     * @var
     */
    public $config_root;
    /**
     * @var
     */
    public $lib_root;
    /**
     * @var
     */
    public $tmp_root;
    /**
     * @var
     */
    public $public_root;
    /**
     * @var
     */
    public $environment;
    /**
     * @var
     */
    public $app_config;
    /**
     * @var Smarty
     */
    public $smarty;
    /**
     * @var
     */
    public $mainView;
    /**
     * @var
     */
    public $template;
    /**
     * @var
     */
    public $views;
    /**
     * @var
     */
    public $autoRender;
    /**
     * @var
     */
    public $requestData;
    /**
     * @var
     */
    public $postData;
    /**
     * @var
     */
    public $getData;
    /**
     * @var
     */
    public $version;
    /**
     * @var
     */
    public $requestUrl;

    /**
     * @var
     */
    public $extractors_root;
    /**
     * @var
     */
    public $query_id;
    /**
     * @var \Quantum\HostedApp
     */
    public $app;
    /**
     * @var \Quantum\Request
     */
    public $request;
    /**
     * @var \Quantum\InternalPathResolver
     */
    public $ipt;
    /**
     * @var \Quantum\Session
     */
    public $session;
    /**
     * @var \Quantum\Output
     */
    public $output;


    /**
     * Quantum constructor.
     */
    public function __construct()
    {
        Quantum\Profiler::enableIfProfilerFileExists();
        Quantum\Profiler::start("Quantum::__construct");


        $this->initKernel();

        $this->importRequiredLibraries();

        $this->autoConfig();
        $this->initActiveRecord();

        $this->kernel->runRequestMiddlewares();

        $this->initSmarty();

        $this->registerAdditionalRoutes();


        $this->createApp();


        Quantum\Profiler::start("Quantum::runControllerDispatch");
        $this->runControllerDispatch();
        Quantum\Profiler::stop("Quantum::runControllerDispatch");

        $this->output();

        Quantum\Profiler::stop("Quantum::__construct");


        $this->shutdown();

    }


    /**
     *
     */
    private function initKernel()
    {
        $this->kernel = Quantum\Kernel::getInstance();
        $this->request = $this->kernel->request;
        $this->ipt = $this->kernel->ipt;
        $this->session = Quantum\Session::getInstance();
        
        $this->setRootFolders();

        $this->setQuantumVars();
    }


    /**
     *
     */
    private function importRequiredLibraries()
    {
        Quantum\Import::library('pagination/paginator_qsa.class.php');
    }

    /**
     *
     */
    private function autoConfig()
    {
        $this->config = $this->kernel->config;

        $this->environment = $this->config->getEnvironment();
        $this->app_config = $this->config->getHostedAppConfig();

        //QM::register("environment", $this->environment);
        //QM::register("app_config", $this->app_config);
    }

    /**
     *
     */
    private function initActiveRecord()
    {
        Quantum\Import::library('activerecord/ActiveRecord.php');

        $cfg = ActiveRecord\Config::instance();
        $cfg->set_model_directory(array($this->models_root, $this->ipt->shared_app_activerecord_models_root));

        //dd($this->environment);

        $conn = array(
            $this->environment->instance => 'mysql://' . $this->environment->db_user . ':' .
                $this->environment->db_password . '@' .
                $this->environment->db_host . '/' .
                $this->environment->db_name . ''

        );

        $cfg->set_connections($conn, $this->environment->instance);
    }

    /**
     *
     */
    private function initSmarty()
    {
        $this->output = $this->kernel->output;
        $this->smarty = $this->output->smarty;

        if ($this->config->isProductionEnvironment())
            $this->output->setCompressOutput(true);

    }


    /**
     *
     */
    private function registerAdditionalRoutes()
    {
        Quantum\RoutesRegistry::addRoutes($this->config->getGlobalRoutes());
        Quantum\RoutesRegistry::addRoutes($this->config->getActiveAppRoutes());

    }


    /**
     *
     */
    private function createApp()
    {
        Quantum\Profiler::start("Quantum::createApp");
        $this->app = Quantum\HostedAppFactory::create();
        $this->callAppMethod("init");
        Quantum\Profiler::stop("Quantum::createApp");
    }

    /**
     *
     */
    private function runControllerDispatch()
    {
        //QM::register("quantum", $this);

        $this->callAppMethod("pre_controller_construct");

        //$this->launcher();

        $this->routeBasedDispatch();
    }

    /**
     *
     */
    private function shutdown()
    {
        $this->callAppMethod("shutdown");

        qm_profiler_html();

        QM::shutdown();
    }


    /**
     *
     */
    private function output()
    {
        Quantum\Profiler::start("Quantum::output");
        if (isset($this->activeController))
            $this->output->addProperties($this->activeController->registry->getProperties());

        $this->callAppMethod("pre_render");

        if (isset($this->activeController))
            $this->callInternalApiControllerFunction($this->activeController, "__pre_render");

        $this->output->render();

        if (isset($this->activeController))
            $this->callInternalApiControllerFunction($this->activeController, "__post_render");

        $this->callAppMethod("post_render");
        Quantum\Profiler::stop("Quantum::output");

    }


    /**
     *
     */
    private function setRootFolders()
    {
        $this->root_folder = $this->ipt->root_folder;
        $this->quantum_root = $this->ipt->quantum_root;
        $this->app_root = $this->ipt->app_root;
        $this->controllers_root = $this->ipt->controllers_root;
        $this->local_root = $this->ipt->local_root;
        $this->models_root = $this->ipt->models_root;
        $this->views_root = $this->ipt->views_root;
        $this->filters_root = $this->ipt->filters_root;
        $this->helpers_root = $this->ipt->helpers_root;
        $this->templates_root = $this->ipt->templates_root;

        $this->kernel_root = $this->ipt->kernel_root;
        $this->system_root = $this->ipt->system_root;
        $this->system_kernel_root = $this->ipt->system_kernel_root;
        $this->system_helpers_root = $this->ipt->system_helpers_root;

        $this->config_root = $this->ipt->config_root;
        $this->lib_root = $this->ipt->lib_root;
        $this->var_root = $this->ipt->var_root;
        $this->tmp_root = $this->ipt->tmp_root;
    }


    /**
     *
     */
    private function setQuantumVars()
    {
        if ($this->request->hasNonEmptyParam('controller'))
        {
            $possible_controller = $this->request->getParam('controller');

            if (!is_string($possible_controller) || !qs($possible_controller)->isAlphaNumericWithSpaceAndDash())
                Quantum\Output::getInstance()->displayAppError('500');

            //$this->controller = Quantum\Security::sanitize_html_string($possible_controller);
        }

        if ($this->request->hasNonEmptyParam('task'))
        {
            $possible_task = $this->request->getParam('task');

            if (!is_string($possible_task) || !qs($possible_task)->isAlphaNumericWithSpaceAndDash())
                Quantum\Output::getInstance()->displayAppError('500');

            //$this->task = Quantum\Security::sanitize_html_string($possible_task);
        }

        if ($this->request->hasNonEmptyParam('object_id'))
        {
            $possible_object_id = $this->request->getParam('object_id');

            if (!is_string($possible_object_id) || !qs($possible_object_id)->isAlphaNumericWithSpaceAndDash())
                Quantum\Output::getInstance()->displayAppError('500');

            //$this->object_id = Quantum\Security::sanitize_html_string($possible_object_id);
        }

        if ($this->request->hasNonEmptyParam('query_id'))
        {
            $possible_query_id = $this->request->getParam('query_id');

            if (!is_string($possible_query_id) || !qs($possible_query_id)->isAlphaNumericWithSpaceAndDash())
                Quantum\Output::getInstance()->displayAppError('500');

            //$this->query_id = Quantum\Security::sanitize_html_string($possible_query_id);
        }

    }


    /**
     *
     */
    private function routeBasedDispatch()
    {
        //$uri = $this->request->getUri();

        $route = $this->config->getCurrentRoute();

        if (empty($route))
            Quantum\ApiException::resourceNotFound();

        $this->controller = $route->get('controller');

        if ($this->config->isCurrentRouteWildcard())
        {
            $possible_task = $this->request->getParam('task', $route->get('method'));

            $this->validateWildcardRequest($possible_task);

            $this->task = Quantum\Security::sanitize_html_string($possible_task);
        }
        else
        {
            $this->task = $route->get('method');
        }

        $name = ucfirst($this->controller);

        $name = qs($name)->ensureRight("Controller")->toStdString();

        $this->createControllerAndCallFunction($name, $this->task);
    }

    private function validateWildcardRequest($task)
    {
        if (qs($task)->startsWith('__'))
            QM::output()->displayAppError('404');

        if (!is_string($task))
            QM::output()->displayAppError('404');

    }


    private function callControllerFunction($controllerName, $controller, $task)
    {
        $reflection = new ReflectionMethod($controller, $task);

        if ($reflection->isProtected() || $reflection->isPrivate())
        {
            QM::output()->displayAppError('404');
        }

        if ($reflection->isPublic())
        {
            call_user_func(array($controller, $task));
            $controller->mainView = "$controllerName/$task.tpl";
            $this->output->setView($this->controller, $task);
        }

        else
        {
            call_user_func(array($controller, 'index'));
            $controller->mainView = "index.tpl";
            $this->output->setView($this->controller, 'index');
        }
    }

    /**
     * @param $controllerName
     * @param $task
     * @throws ReflectionException
     */
    private function createControllerAndCallFunction($controllerName, $task)
    {
        $c = $this->createController($controllerName);

        $this->activeController = $c;

        $this->output->setActiveController($this->activeController);

        $this->callAppMethod("pre_controller_dispatch");
        $this->callInternalApiControllerFunction($c, "__pre_dispatch");

        if (method_exists($c, $task))
        {
            $this->callControllerFunction($controllerName, $c, $task);
        }
        else
        {
            if ($this->config->isCurrentRouteWildcard())
            {
                $task = $this->activeController->task;

                if (!method_exists($c, $task))
                    $task = 'index';

                $this->callControllerFunction($controllerName, $c, $task);
            }
            else
            {
                throw new \InvalidArgumentException('Class method not found:'.$this->task);
            }

        }

        $this->callInternalApiControllerFunction($c, "__post_dispatch");
        $this->callAppMethod("post_controller_dispatch");

        //$this->output->setActiveController($this->activeController);
    }


    /**
     * @param $c
     * @param $method
     */
    private function callInternalApiControllerFunction($c, $method)
    {
        if (method_exists($c, $method))
        {
            try
            {
                $reflection = new ReflectionMethod($c, $method);

                if ($reflection->isProtected())
                {
                    $reflection->setAccessible(true);
                    $reflection->invoke($c, $method);
                    $reflection->setAccessible(false);
                }
            }
            catch (Exception $e)
            {

            }


        }
    }


    /**
     * @param $controllerName
     * @return mixed
     */
    private function createController($controllerName)
    {
        Quantum\Profiler::start("Quantum::createController");

        $c = Quantum\ControllerFactory::create($controllerName, $this->app);

        $this->callInternalApiControllerFunction($c, "__post_construct");

        $this->app->setActiveController($c);

        Quantum\Profiler::stop("Quantum::createController");

        return $c;
    }


    /**
     * @param $method_name
     */
    private function callAppMethod($method_name)
    {
        if (method_exists($this->app, $method_name))
            call_user_func(array($this->app, $method_name));
    }





}