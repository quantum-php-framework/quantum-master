<?php
/**
 * Plugin Name
 *
 * @package           SamplePlugin
 * @author            Sample Company
 * @copyright         2020 Sample Company
 * @license           GPL-2.0-or-later
 *
 * @quantum-plugin
 * Plugin Name:       Sample Plugin
 * Plugin URI:        https://example.com/plugin-name
 * Description:       Description of the plugin.
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

namespace SampleCompany\SamplePlugin;

use Quantum\Events\Event;
use Quantum\Middleware\ValidateAllowedCountries;

class Plugin extends \Quantum\Plugins\Plugin
{
    public function __construct()
    {

    }

    public function init()
    {
        qs ("SampleCompany\SamplePlugin::init")->render();

        //observe_event('init', 'phpinfo');

        observe_event('waka', [$this, 'observer'] );

        observe_event('waka', [$this, 'observer2']);


        dd(dispatch_event('waka', 1));

        //dispatch_event('waka', 'choochoo');

        //dd($this->getFolder()->getPluginEntryHeaders());
    }



    public function observer(\Quantum\Events\Event $event)
    {

        //var_dump($i->getData());
        //var_dump($o);

        //observe_event('pre_render', 'phpinfo');
        //$this->runMiddlewares([ValidateAllowedCountries::class]);
        //dispatch_event('pre_render');
        //$this->getOutput()->set('wome', 1);
        qs ("SampleCompany\SamplePlugin::observer")->render();

        $i = $event->getData();
        return $i + 100;
    }

    function observer2(Event $event)
    {
        qs ("SampleCompany\SamplePlugin::observer2")->render();

        $i = $event->getData();
        return $i + 10;
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