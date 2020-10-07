<?php

namespace Qubes\PluginsManager;

use Quantum\InternalPathResolver;
use Quantum\Plugins\PluginFolder;

class GlobalPluginFoldersExplorer
{
    public function __construct($directories = null)
    {
        $this->plugins = new_vt();

        $this->scan($this->getDirectories());
    }

    public function scan($directories)
    {
        foreach ($directories as $directory)
        {
            $this->scanPluginRoot($directory);
        }
    }


    private function scanPluginRoot($directory)
    {
        $subdirs = qf($directory)->getSubdirectories();

        if (!is_array($subdirs))
            return;

        foreach ($subdirs as $subdir)
        {
            $folder = new PluginFolder($subdir);

            if ($folder->isValid())
            {
                $this->plugins->add($folder);
            }
        }
    }

    public function getPlugins()
    {
        return $this->plugins;
    }

    public function getDirectories()
    {
        $ipt = InternalPathResolver::getInstance();
        $app_directories = qf($ipt->hosted_apps_root)->getSubDirectories();

        $plugin_dirs = new_vt();
        $plugin_dirs->add($ipt->system_plugins_root);
        $plugin_dirs->add($ipt->getSharedAppPluginsRoot());

        foreach ($app_directories as $app_directory)
        {
            $app_plugins_dir = qf($app_directory)->getChildFile('plugins');

            if ($app_plugins_dir->isDirectory()) {
                $plugin_dirs->add($app_plugins_dir->getRealPath());
            }
        }

        return $plugin_dirs->toStdArray();
    }
}