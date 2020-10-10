<?php
/**
 * WorkGroup
*/
class WorkGroup extends ActiveRecord\Model { 

  	static $table_name = 'work_groups';

    static $has_many = array(
      array('areas', 'class_name' => 'WorkArea', 'foreign_key' => 'work_group_id')
    );


    public function getWorkAreas()
    {
        return $this->areas;
    }

    public function getAssignmentsLinkHtml()
    {
        return '<a href="/settings/timeclock/workgroup/'.$this->getId().'/assignments">View Users</a>';
    }

    public function getAssignments()
    {
        return WorkGroupAssignment::find_all_by_work_group_id($this->id);
    }

    public function destroy()
    {
        $this->delete();
    }
        
        
     

}

?>