<?php

namespace Qubes\PluginsManager;


class PluginsController extends \Quantum\Controller
{

    /**
     * Called before calling the main controller action, all environment variables are ready.
     */
    protected function __pre_dispatch()
    {

    }

    public function index()
    {


        $explorer = new GlobalPluginFoldersExplorer();

        $plugin_folders = $explorer->getPlugins();

        $this->set('plugins_count', $plugin_folders->count());
        $this->set('plugins', $plugin_folders->toStdArray());



        $this->output->setMainView('plugins', 'index');

    }
}