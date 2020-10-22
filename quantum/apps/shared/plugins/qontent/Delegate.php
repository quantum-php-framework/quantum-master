<?php

namespace SampleCompany\SamplePlugin;

class PluginDelegate extends \Quantum\Plugins\PluginDelegate
{
    function activate() {say_hi();}
}