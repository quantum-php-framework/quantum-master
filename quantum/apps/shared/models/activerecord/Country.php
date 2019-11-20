<?

/**
 * AccessLevel
*/
class Country extends ActiveRecord\Model {

  	static $table_name = 'countries';

    /*static $belongs_to = array(
      array('user', 'class_name' => 'User', 'foreign_key' => 'user_id')
    ); */

    public static function zipToState($zipcode)
    {
        //$zipcode = qs($zipcode)->upToFirstOccurrenceOf('-');
        /* 000 to 999 */
        $zip_by_state = [
            '--', '--', '--', '--', '--', 'NY', 'PR', 'PR', 'VI', 'PR', 'MA', 'MA', 'MA',
            'MA', 'MA', 'MA', 'MA', 'MA', 'MA', 'MA', 'MA', 'MA', 'MA', 'MA', 'MA', 'MA',
            'MA', 'MA', 'RI', 'RI', 'NH', 'NH', 'NH', 'NH', 'NH', 'NH', 'NH', 'NH', 'NH',
            'ME', 'ME', 'ME', 'ME', 'ME', 'ME', 'ME', 'ME', 'ME', 'ME', 'ME', 'VT', 'VT',
            'VT', 'VT', 'VT', 'MA', 'VT', 'VT', 'VT', 'VT', 'CT', 'CT', 'CT', 'CT', 'CT',
            'CT', 'CT', 'CT', 'CT', 'CT', 'NJ', 'NJ', 'NJ', 'NJ', 'NJ', 'NJ', 'NJ', 'NJ',
            'NJ', 'NJ', 'NJ', 'NJ', 'NJ', 'NJ', 'NJ', 'NJ', 'NJ', 'NJ', 'NJ', 'NJ', 'AE',
            'AE', 'AE', 'AE', 'AE', 'AE', 'AE', 'AE', 'AE', '--', 'NY', 'NY', 'NY', 'NY',
            'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY',
            'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY',
            'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY',
            'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'NY', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA',
            'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA',
            'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA',
            'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', 'PA', '--', 'PA', 'PA',
            'PA', 'PA', 'DE', 'DE', 'DE', 'DC', 'VA', 'DC', 'DC', 'DC', 'DC', 'MD', 'MD',
            'MD', 'MD', 'MD', 'MD', 'MD', '--', 'MD', 'MD', 'MD', 'MD', 'MD', 'MD', 'VA',
            'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA',
            'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA', 'VA',
            'WV', 'WV', 'WV', 'WV', 'WV', 'WV', 'WV', 'WV', 'WV', 'WV', 'WV', 'WV', 'WV',
            'WV', 'WV', 'WV', 'WV', 'WV', 'WV', 'WV', 'WV', 'WV', '--', 'NC', 'NC', 'NC',
            'NC', 'NC', 'NC', 'NC', 'NC', 'NC', 'NC', 'NC', 'NC', 'NC', 'NC', 'NC', 'NC',
            'NC', 'NC', 'NC', 'NC', 'SC', 'SC', 'SC', 'SC', 'SC', 'SC', 'SC', 'SC', 'SC',
            'SC', 'GA', 'GA', 'GA', 'GA', 'GA', 'GA', 'GA', 'GA', 'GA', 'GA', 'GA', 'GA',
            'GA', 'GA', 'GA', 'GA', 'GA', 'GA', 'GA', 'GA', 'FL', 'FL', 'FL', 'FL', 'FL',
            'FL', 'FL', 'FL', 'FL', 'FL', 'FL', 'FL', 'FL', 'FL', 'FL', 'FL', 'FL', 'FL',
            'FL', 'FL', 'AA', 'FL', 'FL', '--', 'FL', '--', 'FL', 'FL', '--', 'FL', 'AL',
            'AL', 'AL', '--', 'AL', 'AL', 'AL', 'AL', 'AL', 'AL', 'AL', 'AL', 'AL', 'AL',
            'AL', 'AL', 'AL', 'AL', 'AL', 'AL', 'TN', 'TN', 'TN', 'TN', 'TN', 'TN', 'TN',
            'TN', 'TN', 'TN', 'TN', 'TN', 'TN', 'TN', 'TN', 'TN', 'MS', 'MS', 'MS', 'MS',
            'MS', 'MS', 'MS', 'MS', 'MS', 'MS', 'MS', 'MS', 'GA', '--', 'KY', 'KY', 'KY',
            'KY', 'KY', 'KY', 'KY', 'KY', 'KY', 'KY', 'KY', 'KY', 'KY', 'KY', 'KY', 'KY',
            'KY', 'KY', 'KY', '--', 'KY', 'KY', 'KY', 'KY', 'KY', 'KY', 'KY', 'KY', '--',
            '--', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH',
            'OH', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH', 'OH',
            'OH', 'OH', 'OH', 'OH', '--', 'IN', 'IN', 'IN', 'IN', 'IN', 'IN', 'IN', 'IN',
            'IN', 'IN', 'IN', 'IN', 'IN', 'IN', 'IN', 'IN', 'IN', 'IN', 'IN', 'IN', 'MI',
            'MI', 'MI', 'MI', 'MI', 'MI', 'MI', 'MI', 'MI', 'MI', 'MI', 'MI', 'MI', 'MI',
            'MI', 'MI', 'MI', 'MI', 'MI', 'MI', 'IA', 'IA', 'IA', 'IA', 'IA', 'IA', 'IA',
            'IA', 'IA', '--', 'IA', 'IA', 'IA', 'IA', 'IA', 'IA', 'IA', '--', '--', '--',
            'IA', 'IA', 'IA', 'IA', 'IA', 'IA', 'IA', 'IA', 'IA', '--', 'WI', 'WI', 'WI',
            '--', 'WI', 'WI', '--', 'WI', 'WI', 'WI', 'WI', 'WI', 'WI', 'WI', 'WI', 'WI',
            'WI', 'WI', 'WI', 'WI', 'MN', 'MN', '--', 'MN', 'MN', 'MN', 'MN', 'MN', 'MN',
            'MN', 'MN', 'MN', 'MN', 'MN', 'MN', 'MN', 'MN', 'MN', '--', 'DC', 'SD', 'SD',
            'SD', 'SD', 'SD', 'SD', 'SD', 'SD', '--', '--', 'ND', 'ND', 'ND', 'ND', 'ND',
            'ND', 'ND', 'ND', 'ND', '--', 'MT', 'MT', 'MT', 'MT', 'MT', 'MT', 'MT', 'MT',
            'MT', 'MT', 'IL', 'IL', 'IL', 'IL', 'IL', 'IL', 'IL', 'IL', 'IL', 'IL', 'IL',
            'IL', 'IL', 'IL', 'IL', 'IL', 'IL', 'IL', 'IL', 'IL', 'IL', '--', 'IL', 'IL',
            'IL', 'IL', 'IL', 'IL', 'IL', 'IL', 'MO', 'MO', '--', 'MO', 'MO', 'MO', 'MO',
            'MO', 'MO', 'MO', 'MO', 'MO', '--', '--', 'MO', 'MO', 'MO', 'MO', 'MO', '--',
            'MO', 'MO', 'MO', 'MO', 'MO', 'MO', 'MO', 'MO', 'MO', '--', 'KS', 'KS', 'KS',
            '--', 'KS', 'KS', 'KS', 'KS', 'KS', 'KS', 'KS', 'KS', 'KS', 'KS', 'KS', 'KS',
            'KS', 'KS', 'KS', 'KS', 'NE', 'NE', '--', 'NE', 'NE', 'NE', 'NE', 'NE', 'NE',
            'NE', 'NE', 'NE', 'NE', 'NE', '--', '--', '--', '--', '--', '--', 'LA', 'LA',
            '--', 'LA', 'LA', 'LA', 'LA', 'LA', 'LA', '--', 'LA', 'LA', 'LA', 'LA', 'LA',
            '--', 'AR', 'AR', 'AR', 'AR', 'AR', 'AR', 'AR', 'AR', 'AR', 'AR', 'AR', 'AR',
            'AR', 'AR', 'OK', 'OK', '--', 'TX', 'OK', 'OK', 'OK', 'OK', 'OK', 'OK', 'OK',
            'OK', '--', 'OK', 'OK', 'OK', 'OK', 'OK', 'OK', 'OK', 'TX', 'TX', 'TX', 'TX',
            'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX',
            'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX',
            'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX',
            'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'TX', 'CO', 'CO', 'CO', 'CO', 'CO', 'CO',
            'CO', 'CO', 'CO', 'CO', 'CO', 'CO', 'CO', 'CO', 'CO', 'CO', 'CO', '--', '--',
            '--', 'WY', 'WY', 'WY', 'WY', 'WY', 'WY', 'WY', 'WY', 'WY', 'WY', 'WY', 'WY',
            'ID', 'ID', 'ID', 'ID', 'ID', 'ID', 'ID', '--', 'UT', 'UT', '--', 'UT', 'UT',
            'UT', 'UT', 'UT', '--', '--', 'AZ', 'AZ', 'AZ', 'AZ', '--', 'AZ', 'AZ', 'AZ',
            '--', 'AZ', 'AZ', '--', '--', 'AZ', 'AZ', 'AZ', '--', '--', '--', '--', 'NM',
            'NM', '--', 'NM', 'NM', 'NM', '--', 'NM', 'NM', 'NM', 'NM', 'NM', 'NM', 'NM',
            'NM', 'NM', '--', '--', '--', '--', 'NV', 'NV', '--', 'NV', 'NV', 'NV', '--',
            'NV', 'NV', '--', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', '--',
            'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA',
            'CA', 'CA', 'CA', 'CA', 'CA', 'CA', '--', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA',
            'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA',
            'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA', 'CA',
            'AP', 'AP', 'AP', 'AP', 'AP', 'HI', 'HI', 'GU', 'OR', 'OR', 'OR', 'OR', 'OR',
            'OR', 'OR', 'OR', 'OR', 'OR', 'WA', 'WA', 'WA', 'WA', 'WA', 'WA', 'WA', '--',
            'WA', 'WA', 'WA', 'WA', 'WA', 'WA', 'WA', 'AK', 'AK', 'AK', 'AK', 'AK'
        ];

        $prefix = substr($zipcode, 0, 3);
        $index = intval($prefix); /* converts prefix to integer */
        $short = $zip_by_state[$index];
        $long = self::convertState($short);

        return (object) ['iso' => $short, 'full' => $long];
    }

    /* -----------------------------------
 * CONVERT STATE NAMES!
 * Goes both ways. e.g.
 * $name = 'Orgegon' -> returns "OR"
 * $name = 'OR' -> returns "Oregon"
 * ----------------------------------- */
    public static function convertState($name) {
        $states = array(
            array('name'=>'Alabama', 'abbr'=>'AL'),
            array('name'=>'Alaska', 'abbr'=>'AK'),
            array('name'=>'Arizona', 'abbr'=>'AZ'),
            array('name'=>'Arkansas', 'abbr'=>'AR'),
            array('name'=>'California', 'abbr'=>'CA'),
            array('name'=>'Colorado', 'abbr'=>'CO'),
            array('name'=>'Connecticut', 'abbr'=>'CT'),
            array('name'=>'Delaware', 'abbr'=>'DE'),
            array('name'=>'Florida', 'abbr'=>'FL'),
            array('name'=>'Georgia', 'abbr'=>'GA'),
            array('name'=>'Hawaii', 'abbr'=>'HI'),
            array('name'=>'Idaho', 'abbr'=>'ID'),
            array('name'=>'Illinois', 'abbr'=>'IL'),
            array('name'=>'Indiana', 'abbr'=>'IN'),
            array('name'=>'Iowa', 'abbr'=>'IA'),
            array('name'=>'Kansas', 'abbr'=>'KS'),
            array('name'=>'Kentucky', 'abbr'=>'KY'),
            array('name'=>'Louisiana', 'abbr'=>'LA'),
            array('name'=>'Maine', 'abbr'=>'ME'),
            array('name'=>'Maryland', 'abbr'=>'MD'),
            array('name'=>'Massachusetts', 'abbr'=>'MA'),
            array('name'=>'Michigan', 'abbr'=>'MI'),
            array('name'=>'Minnesota', 'abbr'=>'MN'),
            array('name'=>'Mississippi', 'abbr'=>'MS'),
            array('name'=>'Missouri', 'abbr'=>'MO'),
            array('name'=>'Montana', 'abbr'=>'MT'),
            array('name'=>'Nebraska', 'abbr'=>'NE'),
            array('name'=>'Nevada', 'abbr'=>'NV'),
            array('name'=>'New Hampshire', 'abbr'=>'NH'),
            array('name'=>'New Jersey', 'abbr'=>'NJ'),
            array('name'=>'New Mexico', 'abbr'=>'NM'),
            array('name'=>'New York', 'abbr'=>'NY'),
            array('name'=>'North Carolina', 'abbr'=>'NC'),
            array('name'=>'North Dakota', 'abbr'=>'ND'),
            array('name'=>'Ohio', 'abbr'=>'OH'),
            array('name'=>'Oklahoma', 'abbr'=>'OK'),
            array('name'=>'Oregon', 'abbr'=>'OR'),
            array('name'=>'Pennsylvania', 'abbr'=>'PA'),
            array('name'=>'Rhode Island', 'abbr'=>'RI'),
            array('name'=>'South Carolina', 'abbr'=>'SC'),
            array('name'=>'South Dakota', 'abbr'=>'SD'),
            array('name'=>'Tennessee', 'abbr'=>'TN'),
            array('name'=>'Texas', 'abbr'=>'TX'),
            array('name'=>'Utah', 'abbr'=>'UT'),
            array('name'=>'Vermont', 'abbr'=>'VT'),
            array('name'=>'Virginia', 'abbr'=>'VA'),
            array('name'=>'Washington', 'abbr'=>'WA'),
            array('name'=>'West Virginia', 'abbr'=>'WV'),
            array('name'=>'Wisconsin', 'abbr'=>'WI'),
            array('name'=>'Wyoming', 'abbr'=>'WY'),
            array('name'=>'Virgin Islands', 'abbr'=>'V.I.'),
            array('name'=>'Guam', 'abbr'=>'GU'),
            array('name'=>'Puerto Rico', 'abbr'=>'PR')
        );

        $return = false;
        $strlen = strlen($name);

        foreach ($states as $state) :
            if ($strlen < 2) {
                return false;
            } else if ($strlen == 2) {
                if (strtolower($state['abbr']) == strtolower($name)) {
                    $return = $state['name'];
                    break;
                }
            } else {
                if (strtolower($state['name']) == strtolower($name)) {
                    $return = strtoupper($state['abbr']);
                    break;
                }
            }
        endforeach;

        return $return;
    } // end function convertState()


        
     

}

?>