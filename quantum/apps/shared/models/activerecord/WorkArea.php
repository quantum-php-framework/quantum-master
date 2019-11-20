<?

/**
 * WorkArea
*/
class WorkArea extends ActiveRecord\Model { 

  	static $table_name = 'work_areas';

    static $belongs_to = array(
      array('workgroup', 'class_name' => 'WorkGroup', 'foreign_key' => 'work_group_id')
    );


    public function getNumCheckedInUsers()
    {
        $count = PerformedWork::count(array('conditions' => array('work_area_id = ? AND completed = ?', $this->id, 0)));

        return $count;
    }


    public function getCurrentWorksBeingPerformed()
    {
        $works = PerformedWork::find_all_by_work_area_id_and_completed($this->id, 0);

        return $works;
    }

    public function getLastCheckInDateForUser($user)
    {
        $works = $this->getAllCompletedWorksForUser($user);

        if (!empty($works))
        {
            $last_work = last($works);
            return to_nicetime($last_work->created_at);
        }

        return "N/A";
    }

    public function getAllCompletedWorksForUser($user)
    {
        $works = PerformedWork::find_all_by_user_id_and_work_area_id_and_completed($user->id, $this->id, 1);

        return $works;
    }

    public function destroy()
    {
        $this->delete();
    }

    
        
        
     

}

?>