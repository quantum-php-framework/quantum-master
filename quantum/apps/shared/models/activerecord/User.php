<?php

/**
 * User
*/
class User extends Quantum\ActiverecordModel {

  	static $table_name = 'users';

    static $belongs_to = array(
        array('account', 'class_name' => 'Account', 'foreign_key' => 'account_id'),
        array('level', 'class_name' => 'AccessLevel', 'foreign_key' => 'access_level_id'),
        array('status', 'class_name' => 'UserStatus', 'foreign_key' => 'status_id')
    );

    public function getFullName()
    {
        return $this->name.' '.$this->lastname;
    }



    public function isActive()
    {
        return $this->status->uri == 'active';
    }



    public function updateLastLogin()
    {
        $this->last_login = date("Y-m-d H:i:s");
        $this->last_known_ip = QM::request()->getIp();
        $this->save();
    }

    public function isUnregistered()
    {
        return ($this->level->uri === "unregistered");
    }

    public function isRegistered()
    {
        if ($this->level->uri === "registered")
            return true;

        if ($this->level->priority > AccessLevel::find_by_uri("registered")->priority)
            return true;

        return false;
    }

    public function isAdmin()
    {
        if ($this->level->uri === "admin")
            return true;

        if ($this->level->priority > AccessLevel::find_by_uri("admin")->priority)
            return true;

        return false;
    }

    public function isSuperAdmin()
    {
        if ($this->level->uri === "superadmin")
            return true;

        if ($this->level->priority > AccessLevel::find_by_uri("superadmin")->priority)
            return true;

        return false;
    }


    public function isRoot()
    {
        return ($this->level->uri === "root");
    }

    public function getAccessLevelName()
    {
        return $this->level->name;
    }

    public function getAccessLevelUri()
    {
        return $this->level->uri;
    }

    public function getAccessLevelPriority()
    {
        return $this->level->priority;
    }

    public function getAccessLevel()
    {
        return $this->level;
    }
}

?>