<?php

use GO\Scheduler;
use Quantum\InternalPathResolver;

require_once(__DIR__ ."/../../composer/vendor/autoload.php");

require_once (__DIR__."/../kernel/system/modules/runtime/Runtime.php");

require_once (__DIR__ . "/../kernel/system/helpers/functions.php");
require_once (__DIR__ . "/../kernel/system/modules/kernel/InternalPathResolver.php");
require_once (__DIR__ . "/../kernel/system/modules/kernel/Autoloader.php");


/**
 * Class QuantumCronScheduler
 */
class QuantumCronScheduler
{

    /**
     * QuantumCronScheduler constructor.
     */
    public function __construct()
    {
        cli_echo('Quantum Cron');

        $start_time = microtime(true);

        global $argv;
        $this->argv = $argv;

        if (isset($this->argv[1]))
            $this->environment_instance = $this->argv[1];
        else
            $this->environment_instance = 'development';

        $this->autoloader = Quantum\Autoloader::getInstance();

        ExternalErrorLoggerService::initRollbarAutomaticErrorHandler();

        $this->ipt = Quantum\InternalPathResolver::getInstance();

        $maintenance_file = $this->ipt->getMaintenanceLockFile();

        if (qf($maintenance_file)->existsAsFile())
        {
            echo "Maintenance mode enabled, aborting cron execution".PHP_EOL;
            exit();
        }

        $this->locks_dir_path = qf($this->ipt->locks_root)->getRealPath();

        $this->scheduler = new Scheduler();

        $apps = Quantum\Config::getInstance()->getHostedApps();

        $hosted_apps_dir = qf($this->ipt->hosted_apps_root);
        foreach ($apps as $app)
        {
            cli_echo('running tasks for app:'.$app['uri']);

            $app_dir = $hosted_apps_dir->getChildFile($app['dir']);
            $config_dir = $app_dir->getChildFile('etc/config');

            $cron_file = $config_dir->getChildFile('cron.php');

            if ($cron_file->existsAsFile())
            {
                $app_crontab_list = include $cron_file->getRealPath();

                if (!empty($app_crontab_list))
                {
                    Quantum\Config::getInstance()->setAppConfig($app);
                    Quantum\Autoloader::getInstance()->initDirectories();
                    Quantum\Autoloader::getInstance()->addAppDirectories();

                    $this->initEnvironment($app);
                    $this->initActiveRecord($app);


                    $this->scheduler->clearJobs();

                    $this->processAppCronTasks($app_crontab_list);

                    $this->scheduler->resetRun()->run();
                }
            }

        }



        $endtime = microtime(true);

        cli_echo('seems all tasks completed');
        cli_echo('total run time: '.($endtime - $start_time).' sec');


    }


    /**
     * @param $app_crontab_list
     */
    public function processAppCronTasks($app_crontab_list)
    {
        if (!isset($app_crontab_list))
            return;

        $this->app = Quantum\HostedAppFactory::create();

        $this->callAppMethod('cli_init');

        $tasks = new_vt($app_crontab_list);

        foreach ($tasks as $task)
        {
            if ($task['enabled'] == true)
            {
                cli_echo('processing task: '.$task['class'].'->'.$task['method']);
                $period = $task['expression'];
                $className = $task['class'];
                $method = $task['method'];
                $allow_overlap = $task['allow_overlap'];

                if (qs($className)->endsWith('Controller'))
                {
                    $this->scheduleControllerMethod($className, $method, $period, $allow_overlap);
                }
                elseif (qs($className)->isNotEmpty())
                {
                    $this->scheduleClassMethod($className, $method, $period, $allow_overlap);
                }
            }
        }

        $this->callAppMethod("cli_shutdown");
    }

    /**
     * @param $controller
     * @param $method
     * @param $period
     * @param $allow_overlap
     */
    public function scheduleControllerMethod($controllerName, $method, $period, $allow_overlap)
    {
        $this->callAppMethod("cli_pre_controller_dispatch");

        $controller = Quantum\ControllerFactory::create($controllerName, $this->app);

        if (!empty($controller))
        {
            if (method_exists($controller, $method))
            {
                $job = $this->scheduler->call(
                    function ($controller, $method)
                    {
                        $this->callInternalApiControllerFunction($controller, '__cli_post_construct');
                        $this->callInternalApiControllerFunction($controller, '__cli_pre_dispatch');

                        call_user_func(array($controller, $method));

                        $this->callInternalApiControllerFunction($controller, '__cli_post_dispatch');
                    },
                    [
                        'controller' => $controller,
                        'method' => $method
                    ]
                );

                $job->at($period);
                $job->inForeground();

                if ($allow_overlap == 0)
                    $job->onlyOne($this->locks_dir_path);
            }
        }

        $this->callAppMethod("cli_post_controller_dispatch");
    }

    /**
     * @param $class
     * @param $method
     * @param $period
     * @param $allow_overlap
     */
    public function scheduleClassMethod($class, $method, $period, $allow_overlap)
    {
        if (method_exists($class, $method))
        {
            $job = $this->scheduler->call(
                function ($class, $method)
                {
                    call_user_func(array($class, $method));
                },
                [
                    'class' => $class,
                    'method' => $method
                ]
            );

            $job->at($period);
            $job->inForeground();

            if ($allow_overlap == 0)
                $job->onlyOne($this->locks_dir_path);
        }
    }

    /**
     * @param $method_name
     */
    private function callAppMethod($method_name)
    {
        if (method_exists($this->app, $method_name))
            call_user_func(array($this->app, $method_name));
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
                }
            }
            catch (Exception $e)
            {

            }


        }
    }

    /**
     * @param $app
     */
    private function initEnvironment($app)
    {
        $ipt = Quantum\InternalPathResolver::getInstance();

        $this->config_root = $ipt->config_root;

        $cfg_file = $this->config_root.'environment.php';

        if (!is_file($cfg_file))
            trigger_error("environment.php not found in config directory", E_USER_ERROR);

        require ($cfg_file);

        if (!isset($QUANTUM_ENVIRONMENTS))
            trigger_error("QUANTUM_ENVIRONMENTS are not set", E_USER_ERROR);

        $current_env = '';

        foreach ($QUANTUM_ENVIRONMENTS as $key => $environment)
        {
            if (qs($environment['domain'])->startsWith($app['uri']) && $environment['instance'] == $this->environment_instance)
            {
                $current_env = (object)$environment;
            }
        }

        if (is_object($current_env))
        {
            Quantum\Config::getInstance()->setEnvironment($current_env);
        }
    }

    /**
     *
     */
    private function initActiveRecord($app)
    {
        Quantum\Import::library('activerecord/ActiveRecord.php');

        $ipt = Quantum\InternalPathResolver::getInstance();

        $this->environment = Quantum\Config::getInstance()->getEnvironment();

        $cfg = ActiveRecord\Config::instance();
        $models_dirs = array(qf($ipt->models_root)->getRealPath(), qf($ipt->shared_app_activerecord_models_root)->getRealPath());

        $cfg->set_model_directory($models_dirs);

        $conn = array(
            $this->environment->instance => 'mysql://' . $this->environment->db_user . ':' .
                $this->environment->db_password . '@' .
                $this->environment->db_host . '/' .
                $this->environment->db_name . ''

        );

        $cfg->set_connections($conn, $this->environment->instance);
    }


}

error_reporting(E_ALL);

set_time_limit(0);

ini_set('memory_limit','-1');

date_default_timezone_set('America/Chicago');


new QuantumCronScheduler();

