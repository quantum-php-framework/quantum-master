<?php
/**
 * Plugin Name
 *
 * @package           AutoRestApi
 * @author            Carlos Barbosa
 * @copyright         2020 QMercium
 * @license           GPL-2.0-or-later
 *
 * @quantum-plugin
 * Plugin Name:       AutoRestApi
 * Plugin URI:        https://example.com/plugin-name
 * Description:       An Auto Rest Api Plugin for Quantum
 * Entry Class:       AutoRestApi\AutoRestApi
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Carlos Barbosa
 * Author URI:        https://example.com
 * Text Domain:       plugin-slug
 * Namespace:         SampleCompany
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace AutoRestApi;

use Quantum\ApiException;

class AutoRestApi extends \Quantum\Plugins\Plugin
{
    /**
     * @var VersionsManager
     */
    private $versions_manager;

    /**
     * @var RequestDecoder
     */
    private $request_decoder;

    public function __construct()
    {

    }

    public function init()
    {
        $versions_file = $this->getFolder()->getChildFile('etc/config/versions.php');
        $this->versions_manager = new VersionsManager($versions_file, $this->getFolder());

        dispatch_event('auto_rest_api_init', $this->versions_manager);
    }


    /**
     * @return bool|mixed
     */
    public function getRoutes()
    {
        if (!self::isActive())
            return [];

        $this->api_routes = [];

        if (empty($this->api_routes)) {
            foreach ($this->versions_manager->getVersions() as $version) {
                $generator = $version->getRouteGenerator();

                $version_routes = $generator->getRoutes();

                $this->api_routes = array_merge($this->api_routes, $version_routes);
            }
        }

        return $this->api_routes;
    }


    public static function isActive()
    {
        return get_active_app_setting('auto_rest_api') == 'enabled';
    }


    public function pre_controller_dispatch()
    {
        $controller_name = get_current_route_setting('controller');

        if ($controller_name != 'AutoRestApi\Controllers\Frontend') {
            return;
        }

        $this->request_decoder = new RequestDecoder($this->versions_manager);

        $model_description = $this->request_decoder->getModelDescription();

        if (!$model_description && !$this->request_decoder->isIndex()) {
            ApiException::invalidParameters();
        }

        $this->validateAccess();

        $api_routes = $this->getRoutes();

        $active_controller = $this->getActiveApp()->getActiveController();

        if ($model_description) {
            $active_controller->setModelDescription($model_description);
        }

        $active_controller->setApiRoutes($api_routes);

        $active_controller->setApiVersion($this->request_decoder->getVersion());
    }

    private function validateAccess()
    {
        dispatch_event('auto_rest_api_before_access_validation', $this->request_decoder->getVersion());

        $middleware = new ValidateAutoRestApiAccess($this->request_decoder->getVersion());

        $middleware->handle(qm_request(), function() {});

        dispatch_event('auto_rest_api_after_access_validation', $this->request_decoder->getVersion());
    }


    /*

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

    */


}