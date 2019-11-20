<?php
/**
 * Created by PhpStorm.
 * User: carlosbarbosachinas
 * Date: 2/7/19
 * Time: 11:10 PM
 */

class MagentoServer extends \Quantum\ActiverecordModel
{
    static $table_name = 'magento_servers';


    public function getWSDLUrl()
    {
        return $this->base_url.'api/?wsdl';
    }

}