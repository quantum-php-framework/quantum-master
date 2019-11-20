<?php

/**
 * Class Auth
 */
class AppSettings extends Quantum\Singleton
{
    /**
     * @var
     */

    /**
     * Auth constructor.
     */
    function __construct()
    {
        $this->app_uri = QM::config()->getHostedAppConfig()->get('uri');
    }


    public static function set($key, $value)
    {
        $instance = AppSettings::getInstance();

        try
        {
            $setting = AppSetting::find_by_app_uri_and_key($instance->app_uri, $key);
        }
        catch (\ActiveRecord\ActiveRecordException $exception)
        {
            dd($exception->getMessage());
        }

        if (empty($setting))
        {
            $setting = new AppSetting();
            $setting->app_uri = $instance->app_uri;
            $setting->key = $key;
            $setting->value = $value;
            $setting->save();
        }
        else
        {
            $setting->value = $value;
            $setting->save();
        }
    }

    public static function get($key, $fallback = false)
    {
        $instance = AppSettings::getInstance();

        try
        {
            $setting = AppSetting::find_by_app_uri_and_key($instance->app_uri, $key);
        }
        catch (\ActiveRecord\ActiveRecordException $exception)
        {
            dd($exception->getMessage());
        }

        if (!empty($setting))
            return $setting->value;

        return $fallback;
    }





}


?>