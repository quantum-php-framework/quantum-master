<?php

/**
 * Plugin Name
 *
 * @package           SimplePlugin
 * @author            Quantum Framework
 * @copyright         2020 Quantum Framework
 * @license           GPL-2.0-or-later
 *
 * @quantum-plugin
 * Plugin Name:       Quantum Simple Plugin
 * Plugin URI:        https://example.com/plugin-name
 * Description:       Sample Plugin
 * Entry Class:       AnotherSampleCompany\SamplePlugin\Plugin
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Carlos Barbosa
 * Author URI:        https://example.com
 * Text Domain:       plugin-slug
 * Namespace:         Qubes
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace AnotherSampleCompany\SamplePlugin;

class Plugin extends \Quantum\Plugins\Plugin
{
    public function __construct()
    {

    }

    public function getRoutes()
    {
        // TODO: Implement getRoutes() method.
    }

    public function init()
    {
        //dd($this->getDelegate());

        //qs ("AnotherSampleCompany\SamplePlugin::init")->render();
    }

    public function pre_controller_construct()
    {
        //qs ("AnotherSampleCompany\SamplePlugin::pre_controller_construct")->render();
    }

    public function pre_controller_dispatch()
    {
        //qs ("AnotherSampleCompany\SamplePlugin::pre_controller_dispatch")->render();
    }

    public function post_controller_dispatch()
    {
        //qs ("AnotherSampleCompany\SamplePlugin::post_controller_dispatch")->render();
    }

    public function pre_render()
    {
        //qs ("AnotherSampleCompany\SamplePlugin::pre_render")->render();
    }

    public function post_render()
    {
        //qs ("AnotherSampleCompany\SamplePlugin::post_render")->render();
    }

    public function shutdown()
    {
        //qs ("AnotherSampleCompany\SamplePlugin::shutdown")->render();
    }
}