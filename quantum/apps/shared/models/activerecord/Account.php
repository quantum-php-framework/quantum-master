<?

/**
 * Account
*/
class Account extends ActiveRecord\Model { 

  	static $table_name = 'accounts';

    /*static $belongs_to = array(
      array('user', 'class_name' => 'User', 'foreign_key' => 'user_id')
    ); */



    public function getUsersCount()
    {
        $items_count = User::count(array('conditions' => 'account_id = ' . $this->id));

        if ($items_count == false)
            return 0;

        return $items_count;
    }

    public function getOrganizationsCount()
    {
        $items_count = Organization::count(array('conditions' => 'account_id = ' . $this->id));

        if ($items_count == false)
            return 0;

        return $items_count;
    }


    function getUsersWithPagination($items_per_page, $low)
    {
        if ($low < 0)
            $low = 0;


        $users = User::find('all', array('limit' => $items_per_page, 'offset' => $low,  'order' => 'id DESC', 'conditions' => array('account_id = ?', $this->id)));

        return $users;
    }

    function getOrganizationsWithPagination($items_per_page, $low)
    {
        if ($low < 0)
            $low = 0;


        $orgs = Organization::find('all', array('limit' => $items_per_page, 'offset' => $low,  'order' => 'id DESC', 'conditions' => array('account_id = ?', $this->id)));

        return $orgs;
    }

    public function getUsersCountWithSearch($string, $access_level_id)
    {
        $string = qs($string)->sanitizeSQL()->trim();

        $wc = "%$string%";

        if ($access_level_id === 'All')
        {
            $items_count = User::count(array('conditions' => array('account_id = ?
        AND (name LIKE ? 
        OR email LIKE ? 
        OR lastname LIKE ? 
        OR username LIKE ? 
        OR quick_login_code LIKE ? 
        OR title LIKE ?)', $this->id, $wc, $wc, $wc, $wc, $wc, $wc)));
        }
        else
        {
            $items_count = User::count(array('conditions' => array('account_id = ? AND access_level_id = ?
        AND (name LIKE ? 
        OR email LIKE ? 
        OR lastname LIKE ? 
        OR username LIKE ? 
        OR quick_login_code LIKE ? 
        OR title LIKE ?)', $this->id, $access_level_id, $wc, $wc, $wc, $wc, $wc, $wc)));
        }

        if ($items_count == false)
            return 0;

        return $items_count;
    }

    function getUsersWithPaginationAndSearch($items_per_page, $low, $string, $access_level_id)
    {
        if ($low < 0)
            $low = 0;

        $string = qs($string)->sanitizeSQL()->trim();

        $wc = "%$string%";

        if ($access_level_id === 'All')
        {
            $users = User::find('all', array('limit' => $items_per_page, 'offset' => $low, 'order' => 'id DESC',
                'conditions' => array('account_id = ?
                                     AND (name LIKE ? 
                                     OR email LIKE ? 
                                     OR lastname LIKE ? 
                                     OR username LIKE ? 
                                     OR quick_login_code LIKE ? 
                                     OR title LIKE ?)', $this->id, $wc, $wc, $wc, $wc, $wc, $wc)));
        }
        else
        {
            $users = User::find('all', array('limit' => $items_per_page, 'offset' => $low, 'order' => 'id DESC',
                'conditions' => array('account_id = ? AND access_level_id = ?
                                     AND (name LIKE ? 
                                     OR email LIKE ? 
                                     OR lastname LIKE ? 
                                     OR username LIKE ? 
                                     OR quick_login_code LIKE ? 
                                     OR title LIKE ?)', $this->id, $access_level_id, $wc, $wc, $wc, $wc, $wc, $wc)));
        }


        return $users;
    }

    function getOrganizationsWithPaginationAndSearch($items_per_page, $low, $string, $access_level_id)
    {
        if ($low < 0)
            $low = 0;

        $string = qs($string)->sanitizeSQL()->trim();

        $wc = "%$string%";

        $organizations = Organization::all(array('conditions' => array('account_id = ? 
        AND (name LIKE ?)', $this->id, $wc)));

        return $organizations;
    }

    public function getOrganizationsCountWithSearch($string)
    {
        $string = qs($string)->sanitizeSQL()->trim();

        $wc = "%$string%";

        $organizations_count = Organization::count(array('conditions' => array('account_id = ? 
        AND (name LIKE ?)', $this->id, $wc)));

        if ($organizations_count == false)
            return 0;

        return $organizations_count;
    }


    public function genMonthsListSinceFirstGatewayClientOrder()
    {
        $orders = POMW_GatewayClientOrder::find('all', array('limit' => 1, 'order' => 'id ASC', 'conditions' => array('account_id = ?', $this->id)));

        $first_order = $orders[0];

        return $this->createMonthsListFromObjectCreatedAt($first_order);

    }

    public function genMonthsListSinceFirstGatewayClientPunchoutSession()
    {
        $sessions = POMW_GatewayClientCustomerPunchoutSession::find('all', array('limit' => 1, 'order' => 'id ASC', 'conditions' => array('account_id = ?', $this->id)));

        $first_session = $sessions[0];

        return $this->createMonthsListFromObjectCreatedAt($first_session);

    }

    private function createMonthsListFromObjectCreatedAt($object)
    {
        $start_month = $object->created_at->format('m');
        $end_month = date('m');
        $start_year = $object->created_at->format('Y');

        $months = new_vt();

        for($m=$start_month; $m<=12; ++$m)
        {

            if($start_month == 12 && $m==12 && $end_month < 12)
            {
                $m = 0;
                $start_year = $start_year+1;
            }

            $key = date('F Y', mktime(0, 0, 0, $m, 1, $start_year));
            $date = date('m Y', mktime(0, 0, 0, $m, 1, $start_year));

            $months->set($date, $key);

            if($m == $end_month)
                break;
        }

        return $months->getProperties();
    }
     

}

?>