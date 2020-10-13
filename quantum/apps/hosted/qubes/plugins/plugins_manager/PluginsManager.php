<?php
/**
 * Plugin Name
 *
 * @package           PluginsManager
 * @author            Quantum Framework
 * @copyright         2020 Quantum Framework
 * @license           GPL-2.0-or-later
 *
 * @quantum-plugin
 * Plugin Name:       Quantum Plugins Manager
 * Plugin URI:        https://example.com/plugin-name
 * Description:       The Main Plugins Manager.
 * Entry Class:       Qubes\PluginsManager\PluginsManager
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

namespace Qubes\PluginsManager;

use Quantum\Events\Event;
use Quantum\Middleware\ValidateAllowedCountries;

class PluginsManager extends \Quantum\Plugins\Plugin
{


    public function __construct()
    {

    }

    public function init()
    {


    }

    public function app_init()
    {
        $this->getActiveApp()->addPluginMenuItem('Plugin Manager', '/plugins', 'si-puzzle');
        $this->getActiveApp()->addSubMenuItem('Plugin Manager', 'Installed Plugins', '/plugins');
        $this->getActiveApp()->addSubMenuItem('Plugin Manager', 'Add New', '/plugins/add');
    }




    public function pre_controller_construct()
    {


        //qs ("SampleCompany\SamplePlugin::pre_controller_construct")->render();
    }

    public function pre_controller_dispatch()
    {


        //qs ("SampleCompany\SamplePlugin::pre_controller_dispatch")->render();
    }

    public function post_controller_dispatch()
    {
        //qs ("SampleCompany\SamplePlugin::post_controller_dispatch")->render();
    }

    public function pre_render()
    {


        //qs ("SampleCompany\SamplePlugin::pre_render")->render();
    }

    public function post_render()
    {
        //qs ("SampleCompany\SamplePlugin::post_render")->render();
    }

    public function shutdown()
    {
        //qs ("SampleCompany\SamplePlugin::shutdown")->render();
    }
    


}