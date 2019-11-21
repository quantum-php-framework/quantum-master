<?php

namespace Quantum;

use Quantum\HMVC\ModuleLocator;

require_once ("Singleton.php");
require_once ("QString.php");
require_once ("File.php");
require_once ("ValueTree.php");
require_once ("Result.php");
require_once (__DIR__.'/../hmvc/ModuleLocator.php');


/**
 * Class Autoloader
 * @package Quantum
 */
class Autoloader extends Singleton
{
    /**
     * @var array
     */
    private $namespaces;
    /**
     * @var array
     */
    private $directories;
    /**
     * @var array
     */
    private $system_directories;
    /**
     * @var array
     */
    private $modules_directories;



    /**
     * Autoloader constructor.
     */
    function __construct()
    {
        $this->moduleLocator = new \Quantum\HMVC\ModuleLocator();

        $this->initDirectories();

        $this->registerAutoLoader();

    }


    public function initDirectories()
    {
        $ipt = InternalPathResolver::getInstance();

        $this->namespaces = array();
        $this->directories = array();
        $this->system_directories = array();

        $this->system_directories = File::newFile($ipt->system_root)->getAllSubDirectories();

        $this->modules_directories = File::newFile($ipt->shared_app_modules_root)->getAllSubDirectories();;

        if (isset($ipt->app_root))
            $this->addDirectory($ipt->app_root, false);

        $this->addAppDirectories();
    }

    public function addAppDirectories()
    {
        $ipt = InternalPathResolver::getInstance();

        $this->addDirectories(array(
            $ipt->shared_app_models_root,
            $ipt->shared_app_controllers_root,
            $ipt->shared_app_resources_root,
            $ipt->shared_app_services_root
        ));

        $this->addDirectory($ipt->lib_root, false);

        $this->removeDuplicatedDirectories();
    }

    /**
     * @param $namespaces
     * @param $remove_duplicates
     */
    public function addNamespaces($namespaces, $remove_duplicates = false)
    {
        $this->namespaces = array_merge($this->namespaces, (array)$namespaces);

        if ($remove_duplicates)
            $this->removeDuplicatedNamespaces();
    }

    /**
     * @param $directories
     * @param $remove_duplicates
     */
    public function addDirectories($directories, $remove_duplicates = true)
    {
        foreach ($directories as $directory)
        {
            $this->addDirectory($directory);
        }

        if  ($remove_duplicates)
            $this->removeDuplicatedDirectories();


    }

    /**
     * @param $directory
     */
    public function addDirectory($directory, $deepscan = true)
    {
        if (!is_dir($directory))
            return;

        array_push($this->directories, $directory);

        $child_directories = recursive_read($directory);

        foreach ($child_directories as $child_directory)
        {
            $child_directory = QString::create(realpath($child_directory))->ensureRight('/')->toStdString();

            if ($deepscan)
                $this->addDirectory($child_directory);
            else
                array_push($this->directories, $child_directory);
        }
    }

    /**
     *
     */
    public function removeDuplicatedDirectories()
    {
        $this->directories = array_unique($this->directories);
    }

    /**
     *
     */
    public function removeDuplicatedNamespaces()
    {
        $this->namespaces = array_unique($this->namespaces);
    }



    /**
     * @param $classname
     * @return mixed
     */
    public function smartLoad($classname)
    {
        if (str_contains($classname, '\\'))
        {
            $namespace = QString::create($classname)->upToLastOccurrenceOf('\\', true)->toStdString();

            $this->addNamespaces($namespace);

            return new $classname;
        }

        return new $classname;
    }
    /**
     * Registers the Quantum Autoloader
     */
    private function registerAutoLoader()
    {
        spl_autoload_register(array('self', 'handle'));
    }

    private function getModuleDirectories($module_directory)
    {
        if (!isset($this->modules_directories))
        {
            $ipt = InternalPathResolver::getInstance();

            $paths = new_vt();

            if (isset($ipt->app_modules_root) && qs($module_directory)->isNotEmpty())
            {
                $app_module_dir = qf($ipt->app_modules_root.$module_directory.'/');

                if ($app_module_dir->isDirectory())
                {
                    $paths->add($app_module_dir->getPath());
                }
            }

            if (isset($ipt->shared_app_modules_root) && qs($module_directory)->isNotEmpty())
            {
                $paths->add($ipt->shared_app_modules_root.$module_directory);
            }

            $this->modules_directories = deepscan_dirs($paths->getArray());
        }

        return $this->modules_directories;
    }

    /**
     * Handles a probably Module load class
     * @return Result
     */
    private function handleProbableModuleClass($className)
    {
        if (!qs($className)->contains('\\'))
            return Result::fail();

        $original_class = $className;

        $namespace = QString::create($className)->upToFirstOccurrenceOf("\\")->toStdString();

        if (!$this->moduleLocator->hasModuleForNamespace($namespace))
            return Result::fail();

        qm_profiler_start('ModuleClassLoad::'.$className);

        $className = QString::create($className)->fromLastOccurrenceOf("\\")->toStdString();

        $module = $this->moduleLocator->getModule($namespace);

        $module_directory = $module->get('directory');

        $module_directories = $this->getModuleDirectories($module_directory);

        if (empty($module_directories))
            return Result::fail();

        $located_file = "";

        foreach($module_directories as $directory)
        {
            $path = $directory.$className.".php";

            if(\file_exists($path))
            {
                $located_file = $path;
                break;
            }
        };

        if (empty($located_file))
        {
            trigger_error("Module Class not found: ".$original_class);
            return Result::fail();
        }

        require_once $located_file;

        qm_profiler_stop('ModuleClassLoad::'.$className);

        return Result::ok();

    }

    /**
     * Thee autoloader...,  you can add more fileNameFormats, for ex: %s.class.php
     */
    private function handle($className)
    {
        //qm_profiler_start('AutoLoad::'.$className);

        $r = $this->handleProbableModuleClass($className);

        if ($r->wasOk())
            return;

        $fileNameFormats = array(
            '%s.php',
        );

        if (qs($className)->contains('Quantum\\'))
            $system_class = true;
        else
            $system_class = false;

        $path = str_ireplace('_', '/', $className);


        if (qs($className)->contains('\\'))
                $className = QString::create($className)->fromLastOccurrenceOf("\\")->toStdString();

        if(@include $path.'.php'){
            //qm_profiler_stop('AutoLoad::'.$className);
            return;
        }

        if ($system_class)
            $directories = $this->system_directories;
        else
            $directories = $this->directories;


        foreach($directories as $directory){
            foreach($fileNameFormats as $fileNameFormat){

                $path = $directory.sprintf($fileNameFormat, $className);
                if(\file_exists($path)){
                    //echo ('loading: '.$path."<br/>");
                    //echo ('className: '.$className."<br/><br/>");
                    //qm_profiler_start('Required::'.$path);

                    require_once $path;
                    //qm_profiler_stop('Required::'.$path);

                    //qm_profiler_stop('AutoLoad::'.$className);
                    return;
                }
            }
        }

        //qm_profiler_stop('AutoLoad::'.$className);


    }

}