<?php
/**
 * AccessLevel
*/
class WafRequestParam extends ActiveRecord\Model {

  	static $table_name = 'waf_request_params';

    /*static $belongs_to = array(
      array('user', 'class_name' => 'User', 'foreign_key' => 'user_id')
    ); */


    public static function getParam($key, $value, $method)
    {
        $param = WafRequestParam::find_by_key_and_method($key, $method);

        if (empty($param))
        {
            $param = new WafRequestParam();
            $param->key = $key;

            if (QM::config()->isDevelopmentEnvironment())
                $param->whitelisted = 1;
            else
                $param->whitelisted = 0;
            $param->method = $method;
            $param->sample = $key."=".$value;
            $param->save();
        }

        return $param;

    }

    public function isBlacklisted()
    {
        return $this->whitelisted != 1;
    }
        
     

}

?>