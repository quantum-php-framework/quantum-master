<?

/**
 * AccessLevel
*/
class AccessLevel extends ActiveRecord\Model {

  	static $table_name = 'access_levels';

    /*static $belongs_to = array(
      array('user', 'class_name' => 'User', 'foreign_key' => 'user_id')
    ); */


    public static function getSearchableLevels()
    {
        $levels = AccessLevel::all();

        $searchables = array();

        foreach ($levels as $level)
        {
            if ($level->uri != 'public')
                array_push($searchables, $level);
        }

        return $searchables;
    }

    public static function getNonPublicLevelsAsKeyPair()
    {
        $levels = new_vt(AccessLevel::all());
        $data = new_vt();

        foreach ($levels as $level)
        {
            if (qs($level->uri)->notEquals('public'))
                $data->set($level->id, $level->name);

        }

        return $data;
    }
        
     

}

?>