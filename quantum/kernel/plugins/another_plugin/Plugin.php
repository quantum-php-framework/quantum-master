<?php

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