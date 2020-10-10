<?php
/**
 * WorkGroup
*/
class ProcurementSupplier extends ActiveRecord\Model {

  	static $table_name = 'procurement_suppliers';

    static $belongs_to = array(
        array('account', 'class_name' => 'Account', 'foreign_key' => 'account_id')
    );

    /*

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

    */

    public function getInternalPunchoutUrl()
    {
        return QM::buildURL('procurement/punchout/'.$this->getId());
    }

    public function getPunchoutSession(User $user)
    {
        $session = PunchoutSession::find_by_user_id_and_supplier_id_and_cancelled($user->id, $this->id, 0);

        if (empty($session))
        {
            $session = new PunchoutSession();
            $session->user_id = $user->id;
            $session->supplier_id = $this->id;
            $session->token = \Quantum\CSRF::create(32);
            $session->buyer_cookie = \Quantum\CSRF::create(16);
            $session->save();
        }

        return $session;
    }
        
     

}

?>