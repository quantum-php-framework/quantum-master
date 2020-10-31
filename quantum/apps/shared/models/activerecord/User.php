<?php

/**
 * User
*/
class User extends Quantum\ActiverecordModel {

  	static $table_name = 'users';

    public function getFullName()
    {
        return $this->name.' '.$this->lastname;
    }


        
        
     

}

?>