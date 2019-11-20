<?

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

    static $has_many = array(
        array('work_group_assignments', 'class_name' => 'WorkGroupAssignment', 'foreign_key' => 'user_id'),
        array('works', 'class_name' => 'PerformedWork', 'foreign_key' => 'user_id')
    );


    public function getFullName()
    {
        return $this->name.' '.$this->lastname;
    }

    public function updateLastLogin()
    {
        $this->last_login = date("Y-m-d H:i:s");
        $this->last_known_ip = QM::request()->getIp();
        $this->save();
    }

    public function createRememberMeToken()
    {
        $token = sha1(Quantum\Utilities::genUUID_V4());

        $this->auto_login_token = $token;
        $this->save();

        return $token;
    }

    public function deleteRememberMeToken()
    {
        $this->auto_login_token = '';
        $this->save();
    }

    public function getTokenForAuthClient($id)
    {
        $token = AuthUserToken::find_by_user_id_and_client_id($this->id, $id);

        if (empty($token))
        {
            $token = new AuthUserToken();
            $token->user_id = $this->id;
            $token->client_id = $id;
            $token->save();
        }

        return $token->refresh();
    }

    public function getPassResetToken()
    {
        if (empty($this->password_recovery_token))
        {
            $this->refreshPassResetToken();
        }

        return $this->password_recovery_token;
    }

    public function refreshPassResetToken()
    {
        $this->password_recovery_token = Quantum\Crypto::genKey();
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

    public function getStatus()
    {
        return $this->status;
    }

    public function getStatusId()
    {
        return $this->status->id;
    }

    public function getStatusName()
    {
        return $this->status->name;
    }

    public function getStatusUri()
    {
        return $this->status->uri;
    }

    public function getWorkAreas()
    {
        $assignments = $this->getWorkGroupAssignments();

        $areas = array();
        foreach ($assignments as $assignment)
        {
            $workgroup = $assignment->group;

            $areas = array_merge($areas, $workgroup->getWorkAreas());
        }

        return $areas;
    }

    public function getWorkGroupAssignments()
    {
        return $this->work_group_assignments;
    }

    public function hasCheckedInToWorkArea()
    {
        $area = $this->getCheckedInWorkArea();

        return (!empty($area));
    }

    public function getCheckedInWorkArea()
    {
        $work = $this->getCurrentUncompleteWork();

        if (!empty($work))
            return $work->area;

        return array();
    }

    public function getCurrentUncompleteWork()
    {
        $works = $this->works;

        foreach ($works as $work)
        {
            if (!$work->isCompleted())
                return $work;
        }

        return array();
    }


    public function getAllowedApps()
    {
        $apps = AuthClient::all();

        foreach ($apps as $key => $app)
        {
            if ($app->uri == 'cclub')
                unset($apps[$key]);
        }

        return $apps;
    }

    public function updatePassword($pwd)
    {
        if (empty($pwd))
            return;

        $this->password = \Quantum\PasswordStorage::create_hash($pwd);
        $this->save();
    }

    public function isActive()
    {
        return $this->status->uri == 'active';
    }

    public function isDisabled()
    {
        return $this->status->uri == 'disabled';
    }

    public function isDeleted()
    {
        return $this->status->uri == 'disabled';
    }




    public static function getAllUsersAsKeyPair()
    {
        $users = new_vt(User::all());
        $data = new_vt();

        foreach ($users as $user)
        {
            if ($user->isActive())
                $data->set($user->getId(), $user->name);

        }

        return $data;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function getAccountId()
    {
        return $this->account_id;
    }

    public function createActivity($type, $data = null)
    {
        $interface = new CreateInterface('UserActivity');

        if (is_array($data))
            $data = json_encode($data);

        $activity = $interface->createModelWithParams([
            'user_id' => $this->id,
            'type' => $type,
            'data' => $data
        ]);

        return $activity;
    }

    public function getAssignedWorkgroupsNames($glue = ",")
    {
        $groups = $this->getWorkGroupAssignments();

        $group_names = new_vt();

        foreach ($groups as $group)
        {
            $group_names->add($group->group->name);
        }

        $group_names->sortAlphabeticallyByValue();

        $string = $group_names->implode($glue);

        return $string;

    }

    public function getEmployeeId()
    {
        return $this->quick_login_code;
    }

    public function getPerformedWorkSummary()
    {
        $works = PerformedWork::find_all_by_user_id($this->id);

        $totals = ['hours'=> 0, 'minutes' => 0, 'seconds' => 0];


        foreach ($works as $work)
        {
            $x = PerformedWork::parse($work->total_time);

            $totals['hours'] += $x['hours'];
            $totals['minutes'] += $x['minutes'];
            $totals['seconds'] += $x['seconds'];
        }

        return $totals;
    }

    public function getTotalClockedHoursAsTimeString()
    {
        $hours = $this->getPerformedWorkSummary();

        return PerformedWork::padTimes($hours['hours']).':'.PerformedWork::padTimes($hours['minutes']).':'.PerformedWork::padTimes($hours['seconds']);
    }

    public function getTotalClockedHours()
    {
        $hours = $this->getPerformedWorkSummary();
        return $hours['hours'];
    }

    public function getTotalClockedMinutes()
    {
        $hours = $this->getPerformedWorkSummary();
        return $hours['minutes'];
    }

    public function getTotalClockedSeconds()
    {
        $hours = $this->getPerformedWorkSummary();
        return $hours['seconds'];
    }

    public function getLastCheckedInArea()
    {
        $works = $this->works;

        if (!empty($works))
        {
            $last_work = last($works);
            return $last_work->area->name;
        }
        return "";
    }

    public function getPerformedWorksWithPagination($items_per_page, $low)
    {
        if ($low < 0)
            $low = 0;

        $works = PerformedWork::find('all', array('limit' => $items_per_page, 'offset' => $low,  'order' => 'id DESC', 'conditions' => array('user_id = ?', $this->id)));

        return $works;
    }


    public static function getUsersByAccessLevelUri($level_uri)
    {
        $level = AccessLevel::find_by_uri($level_uri);

        $users = User::find_all_by_access_level_id($level->id);

        return $users;
    }

    public function getAllowedProcurementSuppliers()
    {
        $suppliers = ProcurementSupplier::find_all_by_account_id($this->getAccountId());

        return $suppliers;
    }
        
        
     

}

?>