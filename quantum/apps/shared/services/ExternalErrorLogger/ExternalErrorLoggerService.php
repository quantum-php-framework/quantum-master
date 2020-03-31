<?php

use Quantum\Serialize\Serializer;
use \Rollbar\Rollbar;
use \Rollbar\Payload\Level;

class ExternalErrorLoggerService
{

    public static function error($exception, $extra_data = null)
    {
        $exception = self::normalize($exception);

        Rollbar::init(self::getRollbarConfig());

        $response = Rollbar::log(Level::error(), $exception, $extra_data);

        return $response;
    }

    public static function info($exception, $extra_data = null)
    {
        $exception = self::normalize($exception);

        Rollbar::init(self::getRollbarConfig());

        $response = Rollbar::log(Level::info(), $exception, $extra_data);

        return $response;
    }


    public static function getRollbarConfig()
    {
        return array(
            'access_token' => '40ef8c962b1f4104991c053307789308',
            'environment' => 'production'
        );
    }


    public static function normalize($data)
    {
        if (is_array($data) || is_object($data))
            $data = Serializer\Json::serialize($data);

        if (!is_string($data))
            $data = (string)$data;

        return $data;
    }


}

?>