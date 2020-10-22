<?php
/**
 * Plugin Name
 *
 * @package           Qontent
 * @author            Carlos Barbosa
 * @copyright         2020 Sample Company
 * @license           GPL-2.0-or-later
 *
 * @quantum-plugin
 * Plugin Name:       Qontent
 * Plugin URI:        https://example.com/plugin-name
 * Description:       A Content Management System for Quantum.
 * Entry Class:       Qontent\Qontent
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Your Name
 * Author URI:        https://example.com
 * Text Domain:       plugin-slug
 * Namespace:         SampleCompany
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Qontent;

use Qontent\Entities\PostRouteHandler;
use Quantum\PhinxMigrationRunner;

class Qontent extends \Quantum\Plugins\Plugin
{
    public function __construct()
    {

    }

    public function init()
    {
        if (self::isFrontendActive()) {
            observe_event('route_not_found', [$this, 'handler_404']);
        }
    }


    public function app_init()
    {
        if (self::isBackendActive())
        {
            $this->getActiveApp()->addPluginMenuItem('Qontent', '/qontent', 'si-note', 10);
            $this->getActiveApp()->addSubMenuItem('Qontent', 'All Posts', '/qon');
            $this->getActiveApp()->addSubMenuItem('Qontent', 'Add New', '/qontent/new');
            $this->getActiveApp()->addSubMenuItem('Qontent', 'Settings', '/qontent/settings');
        }

    }

    /**
     * @return bool|mixed
     */
    public function getRoutes()
    {
        if (self::isBackendActive()) {
            return $this->includePluginFolderFile('/etc/config/backend_routes.php');
        }

        if (self::isFrontendActive()) {
            return $this->includePluginFolderFile('/etc/config/frontend_routes.php');
        }

        return [];
    }

    public function handler_404()
    {
        $handler = new PostRouteHandler();

        return $handler->genRoute();
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

    public static function isFrontendActive()
    {
        return get_active_app_setting('qontent_frontend') == 'enabled';
    }

    public static function isBackendActive()
    {
        return get_active_app_setting('qontent_backend') == 'enabled';
    }


}