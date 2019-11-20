<?

/**
 * PerformedWork
*/



class PerformedWork extends ActiveRecord\Model { 

  	static $table_name = 'performed_works';

    static $belongs_to = array(
        array('area', 'class_name' => 'WorkArea', 'foreign_key' => 'work_area_id'),
        array('user', 'class_name' => 'User', 'foreign_key' => 'user_id')
    );


    public function isCompleted()
    {
        return $this->completed === 1;
    }

    public function updateTotalTime()
    {
        $check_in = new DateTime($this->check_in);
        $since_start = $check_in->diff(new DateTime($this->check_out));

        $total_hours = $since_start->h;

        if ($since_start->d)
        {
            $total_hours += ($since_start->d * 24);
        }

        $time = self::padTimes($total_hours) .":". self::padTimes($since_start->m) .":". self::padTimes($since_start->s);
        $this->total_time = $time;

        $this->save();


    }


    public static function sumTimes($current, $timeToSum)
    {

    }

    public static function parse($timeString)
    {
        $d = qs($timeString)->explode(':');

        if (count($d) == 3)
        {
            $a = ['hours' => self::padTimes($d[0]), 'minutes' => self::padTimes($d[1]), 'seconds' => self::padTimes($d[2])];

            return $a;
        }

        return ['hours'=> 0, 'minutes' => 0, 'seconds' => 0];

    }

    public static function padTimes($number)
    {
        $number = (string)$number;

        if (strlen($number) == 1)
            $number = "0".$number;

        return $number;
    }

    public function getWorkAreaName()
    {
        return $this->area->name;
    }


        
        
     

}

?>