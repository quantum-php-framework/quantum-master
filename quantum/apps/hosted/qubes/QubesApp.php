<?php

/**
 * A Quantum Hosted App
*/

namespace Qubes;

use PageCacheMiddleware;
use QM;
use ValidateAppAccess;
use ValidateAppRoutes;
use ValidateAppRoutesRateLimit;
use ValidateRouteAccessLevel;

class QubesApp extends \Quantum\HostedApp
{
    var $template_manager;


    function __construct()
    {
        parent::__construct();
        $this->template_manager = new TemplateManager();
    }

    public function init()
    {
        $this->runMiddlewares([ValidateAppAccess::class,
            ValidateAppRoutes::class,
            ValidateAppRoutesRateLimit::class,
            \ValidateRouteAccess::class]);
    }

    public function pre_controller_construct()
    {

    }

    public function setActiveController($controller)
    {
         parent::setActiveController($controller);
    }

    public function pre_controller_dispatch()
    {
        $this->output()->setTemplate('qubes');

        $this->output()->set('active_menu', qs(QM::config()->getCurrentRouteUri()));

        $this->output()->set('qm_version', QM_KERNEL_VERSION);

        $this->output()->set('qubes_version', $this->getConfig()->get('version'));
    }

    public function post_controller_dispatch()
    {

    }

    public function pre_render()
    {

    }

    public function post_render()
    {

    }

    public function shutdown()
    {

    }

    public function addPluginMenuItem($visible_name, $uri, $icon_class = null)
    {
        $this->template_manager->addMenuItem($visible_name, $uri, $icon_class);
    }

    public function addSubMenuItem($parent, $visible_name, $uri)
    {
        $this->template_manager->addSubMenuItem($parent, $visible_name, $uri);
    }

}