<?php

namespace Qubes;

use Quantum\HMVC\Module;

class MenuItem
{
    var $name;
    var $uri;
    var $subitems;
    var $icon_class;

    public function __construct($name, $uri, $icon_class = null)
    {
        $this->name = $name;
        $this->uri = $uri;
        $this->icon_class = $icon_class;
        $this->subitems = new_vt();
    }

    public function addSubItem($name, $uri)
    {
        $this->subitems->set($name, new MenuItem($name, $uri));
    }

    public function hasSubItems()
    {
        return !$this->subitems->isEmpty();
    }

    public function getSubItemsNum()
    {
        return $this->subitems->count();
    }

    public function getSubItem($index)
    {
        return $this->subitems[$index];
    }

    public function getSubItems()
    {
        return $this->subitems->toStdArray();
    }


}

class TemplateManager extends Module
{
    var $menu_items;

    public function __construct()
    {
        $this->menu_items = new_vt();

        observe('pre_render', [$this, 'add_plugin_menus_to_output']);
    }

    public function addMenuItem($visible_name, $uri, $icon_class = null)
    {
        $this->menu_items->set($visible_name, new MenuItem($visible_name, $uri, $icon_class));
    }

    public function addSubMenuItem($parent, $visible_name, $uri)
    {
        $item = $this->menu_items->get($parent);

        if ($item)
        {
            $item->addSubItem($visible_name, $uri);
        }
    }


    public function add_plugin_menus_to_output()
    {
        $this->getOutput()->set('plugin_menus', $this->menu_items->toStdArray());
    }



}