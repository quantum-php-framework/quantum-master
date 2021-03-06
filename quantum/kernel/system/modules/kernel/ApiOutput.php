<?php

namespace Quantum;

/**
 * Class ApiOutput
 * @package Quantum
 */
class ApiOutput
{

    /**
     * ApiOutput constructor.
     */
    function __construct()
    {
   
    }

    /**
     *
     */
    public static function send_json_headers()
    {
        header("Content-Type: application/json");
        header("Cache-Control: no-store");
        header("Pragma: no-cache");
    }

    /**
     *
     */
    public static function send_js_headers()
    {
        header("Content-Type: text/javascript");
        header("Cache-Control: no-store");
        header("Pragma: no-cache");
    }

    /**
     *
     */
    public static function send_xml_headers()
    {
        header("Content-Type: text/xml");
        header("Cache-Control: no-store");
        header("Pragma: no-cache");
    }

    /**
     *
     */
    public static function send_serialized_headers()
    {
        header("Content-Type: application/vnd.php.serialized");
        header("Cache-Control: no-store");
        header("Pragma: no-cache");
    }

    /**
     *
     */
    public static function send_yaml_headers()
    {
        header("Content-Type: application/yaml");
        header("Cache-Control: no-store");
        header("Pragma: no-cache");
    }

    /**
     *
     */
    public static function send_text_headers()
    {
        header("Content-Type: text/text");
        header("Cache-Control: no-store");
        header("Pragma: no-cache");
    }

    /**
     * @param $collection
     * @param array $allowed_params
     * @return array
     */
    public static function convertActiveRecordCollectionToStdArray($collection, $allowed_params = array())
    {
        $data = array();

        foreach ($collection as $model)
        {
            $model_data = $model->to_array();

            $datum = $model_data;

            if (!empty($allowed_params))
            {
                $datum = array();

                foreach ($allowed_params as $param)
                {
                    if (isset($model_data[$param]))
                    {
                        $datum[$param] = $model_data[$param];
                    }
                }
            }

            array_push($data, $datum);
        }

        return $data;
    }

    /**
     * @param $collection
     * @param array $allowed_params
     */
    public static function modelsCollectionAdaptableOutput($collection, $allowed_params = array())
    {
        $data = self::convertActiveRecordCollectionToStdArray($collection, $allowed_params);

        self::adaptableOutput($data);
    }

    /**
     * @param $data
     */
    public static function adaptableOutput($data, $shutdown = true, $pretty_print_json = false)
    {
        if (!isset($_REQUEST['format']) || $_REQUEST['format'] == 'json') {

            self::send_json_headers();

            if ($pretty_print_json) {
                echo json_encode($data,JSON_PRETTY_PRINT);
            }
            else {
                echo json_encode($data);
            }

            if ($shutdown)
                Kernel::shutdown();

            return;
        }

        if (isset($_REQUEST['callback']))
        {
            $cb = preg_replace("/[^][.\\'\\\"_A-Za-z0-9]/", '', $_GET['callback']);
            self::send_js_headers();
            print sprintf('%s(%s);', $cb, json_encode($data));

            if ($shutdown)
                Kernel::shutdown();

            return;
        }

        $format = $_REQUEST['format'];

        switch ($format)
        {
            case 'xml':
                self::send_xml_headers();
                $data = ArrayToXml::convert($data);
                echo $data;

                if ($shutdown)
                    Kernel::shutdown();

                return;
                break;

            case 'serialized':
                self::send_text_headers();
                echo serialize($data);

                if ($shutdown)
                    Kernel::shutdown();

                return;
                break;

            case 'text':
                self::send_text_headers();
                echo print_r($data);

                if ($shutdown)
                    Kernel::shutdown();

                return;
                break;

            case 'yaml':
                self::send_text_headers();
                $yaml =  \yaml_emit($data);
                echo $yaml;

                if ($shutdown)
                    Kernel::shutdown();

                return;
                break;

            case 'dbug':
                self::send_text_headers();
                var_dump($data);

                if ($shutdown)
                    Kernel::shutdown();

                return;

                break;
        }

    }

}