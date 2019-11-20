<?php


namespace WAF;

/**
 * Class WAF
 * @package Quantum
 */
class Firewall
{
    /**
     * @param $var
     */
    public static function ensureModelExists($var)
    {
        if (!instance_of($var, \ActiveRecord\Model::class))
        {
            \Quantum\Output::getInstance()->displayAppError('500');
        }
    }

    /**
     * @param $param
     */
    public static function ensureParamNotEmpty($param)
    {
        if (empty($param))
        {
            \Quantum\Output::getInstance()->displayAppError('500');
        }
    }
}