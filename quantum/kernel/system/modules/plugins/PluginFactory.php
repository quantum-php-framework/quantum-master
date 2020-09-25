<?php


namespace Quantum\Plugins;


class PluginFactory
{
    public function __construct( $folder)
    {
        $this->folder = $folder;
    }


    public function build()
    {
        $entry_file = qf($this->folder->getPluginEntryFile());


        include $entry_file->getRealPath();

        $classes = get_declared_classes();

        $last_class = end($classes);

        $plugin = new $last_class;
        $plugin->_setFolder($this->folder);
        $plugin->_setEntryClassName($last_class);

        return $plugin;
    }

}