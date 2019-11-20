<?php

namespace Quantum\HMVC;


/**
 * Class ModuleController
 * @package Quantum\HMVC
 */
class ModuleLocator
{

    public function __construct()
    {
        $this->modules = new_vt(include qf(\Quantum\InternalPathResolver::getInstance()->config_root)->getChildFile('modules.php')->getRealPath());
        //dd($this->modules);
    }

    public function hasModuleForNamespace($namespace)
    {
        if (!is_string($namespace))
            return false;

        return $this->modules->has($namespace);
    }

    public function getModule($namespace)
    {
        return new_vt($this->modules->get($namespace));
    }


}