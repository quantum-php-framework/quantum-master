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




        $classes = get_declared_classes();
        include $entry_file->getRealPath();
        $diff = array_diff(get_declared_classes(), $classes);
        $last_class = reset($diff);

        //$last_class = get_cl

        $plugin = new $last_class;
        $plugin->_setFolder($this->folder);
        $plugin->_setEntryClassName($last_class);

        return $plugin;
    }



}