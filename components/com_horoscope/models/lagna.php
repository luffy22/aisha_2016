<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class HoroscopeModelLagna extends JModelItem
{
    public $fname;
    public $gender;
    public $dob;
    public $tob;
    public $lon;
    public $lat;
    public $tmz;
    // Get lagna using sidereal tables and corrections
    public function getLagna($user_details)
    {
        
        // Assigning the variables
        $this->fname        = $user_details['fname'];
        $this->gender       = $user_details['gender'];
        $this->dob          = $user_details['dob'];
        $this->tob          = $user_details['tob'];
        $this->lon          = $user_details['lon'];
        $this->lat          = $user_details['lat'];
        $this->tmz          = $user_details['tmz'];
        
        $tob        = explode(":",$this->tob);
        if($tob[3]=="PM")
        {
            $tob[0] = $tob[0]+12;
            $tob1   = strtotime($tob[0].":".$tob[1].":".$tob[2]);
        }
        else
        {
            $tob1   = strtotime($tob[0].":".$tob[1].":".$tob[2]);
        }
        $this->tob          = $tob1;
        
      echo $this->calculateLagna();
    }
    // Method to get the sidereal Time
    public function getSiderealTime()
    {
        $lon            = explode(":", $this->lon);
        $dob            = explode("/",$this->dob);
        $monthNum       = $dob[1];  // The month in number format (ex. 06 for June)
        $monthName      = date("F", mktime(0, 0, 0, $monthNum, 10));		// month in word format (ex. June/July/August)
        
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        
        $query          -> select($db->quoteName('Sidereal'));
        $query          -> from($db->quoteName('#__sidereal_1'));
        $query          -> where($db->quoteName('Month').'='.$db->quote($monthName).'AND'.
                                 $db->quoteName('Date')."=".$db->quote($dob[2]));
        $db             ->setQuery($query);
        $count          = count($db->loadResult());
        $row            =$db->loadAssoc();
        
        if($count>0)
        {
            $get_sidetime_year                          = strtotime($row['Sidereal']);
            $date					= new DateTime($this->dob);		// Datetime object with user date of birth
            $date					->setTimeStamp($get_sidetime_year);		// time of birth for user
                      
            $query      ->clear();
            if($monthName == "January" || $monthName == "February")
            {
                $query      ->select($db->quoteName('corr_time'));
                $query      ->from($db->quoteName('#__sidereal_2'));
                $query      ->where($db->quoteName('Year').'='.$db->quote($dob[0]).' AND '.
                                    $db->quote('leap').'='.'*');
            }
            else
            {
                $query      ->select($db->quoteName('corr_time'));
                $query      ->from($db->quoteName('#__sidereal_2'));
                $query      ->where($db->quoteName('Year').'='.$db->quote($dob[0]));
                //$query_sideyear			= mysqli_query($con, "SELECT corr_time FROM jv_sidereal_2 WHERE Year='".$dob_split[0]."'");
            }
            $db                 ->setQuery($query);
            unset($count);
            $count              = count($db->loadResult());
            
            $time_diff          = $db->loadAssoc();
            $correction         = $time_diff['corr_time'];      // correction time diff using sidereal_2 table
            
            $corr_diff          = substr($correction, 0,1);     // the positive/negative sign
            $corr_time		= substr($correction,1);        // the time diff in mm:ss format
            $diff               = explode(":", $corr_time);
            
            if($corr_diff == "-")
            {
                //$get_sidereal_timediff	= 
                if($diff[0] != "00"||$diff[0] != "0")
                {
                    $date	->sub(new DateInterval('PT'.$diff[0].'M'.$diff[1].'S'));
                }
                else
                {
                    $date	->sub(new DateInterval('PT'.$diff[1].'S'));
                }
               
            }
            else if($corr_diff == "+")
            {
                //$get_sidereal_timediff	= 
                if($diff[0] != "00"||$diff[0] != "0")
                {
                    $date	->add(new DateInterval('PT'.$diff[0].'M'.$diff[1].'S'));
                }
                else
                {
                    $date	->add(new DateInterval('PT'.$diff[1].'S'));
                }
            }
           
            $query              ->clear();
            $query              = "select corr_sign, st_correction FROM jv_sidereal_3 WHERE longitude >= '".($lon[0].'.'.$lon[1])."'
                                    order by abs(longitude - '".($lon[0].'.'.$lon[1])."') limit 1";
            $db                 ->setQuery($query);
            unset($count);
            $count              = count($db->loadResult());
            $sid_corr           = $db->loadAssoc();     // sidereal correction in seconds
            if($sid_corr['corr_sign'] == "-")
            {
                    //$get_sidereal_timediff	= 
                //$date           ->sub(new DateInterval('PT'.$corr_mins.'M'.$corr_secs.'S'));
                $diff           = explode(".",$sid_corr['st_correction']);
                if($diff[0] != "00"||$diff[0] != "0")
                {
                        $date	->sub(new DateInterval('PT'.$diff[0].'M'.$diff[1].'S'));
                }
                else
                {
                        $date	->sub(new DateInterval('PT'.$diff[1].'S'));
                }
            }
            else if($corr_diff['corr_sign'] == "+")
            {
                //$get_sidereal_timediff	= 
                $date	->add(new DateInterval('PT'.$corr_mins.'M'.$corr_secs.'S'));
                $diff	= explode(".",$corr_diff['st_correction']);
                if($diff[0] != "00"||$diff[0] != "0")
                {
                    $date   ->add(new DateInterval('PT'.$diff[0].'M'.$diff[1].'S'));
                }
                else
                {
                    $date   ->add(new DateInterval('PT'.$diff[1].'S'));
                }
            }
            return $date->format('H:i:s');
        }
        //longitude >= '".($lon)."'
    }
    public function getLmt()
    {
        $lon        = explode(":", $this->lon);
        $lat        = explode(":", $this->lat);
        $gmt        = "GMT".$this->tmz;
        $dob        = $this->dob;
        $tob        = $this->tob;
        
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        
        $query          ->select($db->quoteName('std_meridian'));
        $query          ->from($db->quoteName('#__std_meridian'));
        $query          ->where($db->quoteName('timezone').'='.$db->quote($gmt));
        $db             ->setQuery($query);
        $count          = count($db->loadResult());
        
        if($count > 0)
        {
            $meridian           = $db->loadAssoc();
            $std_meridian       = explode(".",$meridian['std_meridian']);
            
            $std_lon_min        = $std_meridian[0];
            $std_lon_sec        = $std_meridian[1];
            $loc_lon_min        = $lon[0];
            $loc_lon_sec        = $lon[1];
           
            $new_std_lon_sec	= ($std_lon_min*60*4)+($std_lon_sec*4);		// convert minutes into seconds & multiply by 4 then add to seconds multiplied by 4
            $new_loc_lon_sec	= ($loc_lon_min*60*4)+($loc_lon_sec*4);			// convert minutes into seconds & multiply by 4 then add to seconds multiplied by 4
           
            if($new_std_lon_sec > $new_loc_lon_sec)
            {
                $new_diff	= $new_std_lon_sec - $new_loc_lon_sec;
                $new_diff	= gmdate('H:i:s', $new_diff);
            }
            else
            {
                $new_diff	= $new_loc_lon_sec	- $new_std_lon_sec;
                $new_diff	= gmdate('H:i:s', $new_diff);
            }
     
            // Computation to check Sidereal Time
            $date		= new DateTime($dob);		// Datetime object with user date of birth
            $date		->setTimeStamp($tob);		// time of birth for user
            $date		->format('H:i:s');
            $diff		= explode(":",$new_diff);
           
            if($std_loc_min > $loc_lon_min)
            {
                
                $date			->sub(new DateInterval('PT'.$diff[0].'H'.$diff[1].'M'.$diff[2].'S'));
                $get_sidereal_hour	= explode(":", $date->format('H:i:s'));	
                $sidereal_hr            = $get_sidereal_hour[0];
                $sidereal_min           = $get_sidereal_hour[1];
                
                $query                  ->clear();
                $query                  ->select($db->quoteName('min'));
                $query                  ->from($db->quoteName('#__sidereal_4'));
                $query                  ->where($db->quoteName('hour').'='.$db->quote($sidereal_hr));
                $db                     ->setQuery($query);
                $sid_diff_min		= $db->loadAssoc();
                $sid_diff_1		= explode(":",$sid_diff_min['min']);
		
                if($sid_diff_1[0] != "00")
                {
                        $date		->add(new DateInterval('PT'.$sid_diff_1[0].'M'.$sid_diff_1[1].'S'));
                }
                else
                {
                        $date		->add(new DateInterval('PT'.$sid_diff_1[1].'S'));
                }
                
                $query                  ->clear();
                $query                  ->select($db->quoteName('diff'));
                $query                  ->from($db->quoteName('#__sidereal_5'));
                $query                  ->where($db->quoteName('min').'>='.$db->quote($sidereal_min));
                $db                     ->setQuery($query);
                $sid_diff_sec		= $db->loadAssoc();
                
                $sid_diff_2		= explode(":", $sid_diff_sec['diff']);
                $date			->add(new DateInterval('PT'.$sid_diff_2[1].'S'));
            }
            else
            {

                $date			->add(new DateInterval('PT'.$diff[0].'H'.$diff[1].'M'.$diff[2].'S'));
                $get_sidereal_hour	= explode(":", $date->format('H:i:s'));	
                $sidereal_hr            = $get_sidereal_hour[0];
                $sidereal_min           = $get_sidereal_hour[1];
  
                $query                  ->clear();
                $query                  ->select($db->quoteName('min'));
                $query                  ->from($db->quoteName('#__sidereal_4'));
                $query                  ->where($db->quoteName('hour').'='.$db->quote($sidereal_hr));
                $db                     ->setQuery($query);
                $sid_diff_min		= $db->loadAssoc();
                $sid_diff_1		= explode(":",$sid_diff_min['min']);
			
                if($sid_diff_1[0] != "00")
                {
                    $date		->add(new DateInterval('PT'.$sid_diff_1[0].'M'.$sid_diff_1[1].'S'));
                }
                else
                {
                    $date		->add(new DateInterval('PT'.$sid_diff_1[1].'S'));
                }
                
                $query                  ->clear();
                $query                  ->select($db->quoteName('diff'));
                $query                  ->from($db->quoteName('#__sidereal_5'));
                $query                  ->where($db->quoteName('min').'>='.$db->quote($sidereal_min));
                $db                     ->setQuery($query);
                $sid_diff_sec		= $db->loadAssoc();
               
                $sid_diff_2		= explode(":", $sid_diff_sec['diff']);
                $date			->add(new DateInterval('PT'.$sid_diff_2[1].'S'));  
            }
            return $date->format('H:i:s');
        }
        else
        {
            return "No matching results";
        }
    }
    public function calculateLagna()
    {
        $lat                    = explode(":",$this->lat);
        $newlat                 = $lat[0].'.'.$lat[1];
        
        $siderealTime		= strtotime($this->getSiderealTime());
	$lmt			= explode(":",$this->getLmt());
        $dob                    = $this->dob;
        
        $tob                    = gmdate('H:i:a', $this->tob);
        $tob                    = explode(":",$tob);
        
        $doy			= explode("/", $dob);
        $date                   = new DateTime($this->doy);
        $date                   ->setTimestamp($siderealTime);
        if($tob[2]== "pm")
        {
            $date               ->add(new DateInterval('PT'.$lmt[0].'H'.$lmt[1].'M'.$lmt[2].'S'));
        }
        else
        {
            $date               ->sub(new DateInterval('PT'.$lmt[0].'H'.$lmt[1].'M'.$lmt[2].'S'));
        }
        
        $corr_sidereal          = explode(":",$date->format('H:i:s'));
        $corr_sid_hr            = $corr_sidereal[0];
        $corr_sid_min           = $corr_sidereal[1];
        
        $up_min			= ceil($corr_sid_min/10)*10;
        $down_min               = floor($corr_sid_min/10)*10;
        $db                     = JFactory::getDbo();  // Get db connection
        $query                  = $db->getQuery(true);
        
        $query                  ->clear();
        $query                  = "SELECT * FROM jv_lahiri_7 WHERE latitude<='".$newlat."' AND hour='".$corr_sid_hr."' AND minute='".$up_min."' ORDER BY abs(latitude-'".$newlat."') limit 1";
        $db                     ->setQuery($query);
        
        $get_up_lagna           = $db->loadAssoc();
        $lagna                  = $get_up_lagna['lagna_sign'];
        
        $up_time		= strtotime("0:".$get_up_lagna['lagna_degree'].":".$get_up_lagna['lagna_min']);
        
        $query                  ->clear();
        $query                  = "SELECT * FROM jv_lahiri_7 WHERE latitude<='".$newlat."' AND hour='".$corr_sid_hr."' AND minute='".$down_min."' ORDER BY abs(latitude-'".$newlat."') limit 1";
        $db                     ->setQuery($query);
        $get_down_lagna		= $db->loadAssoc();
        
        $down_sign		= $get_down_lagna['lagna_sign'];
        $down_deg               = $get_down_lagna['lagna_degree'];
        $down_sec               = $get_down_lagna['lagna_min'];
        $date			= new DateTime($dob);
        $date			->setTimeStamp($up_time);
        $date			->sub(new DateInterval('PT'.$get_down_lagna['lagna_degree'].'M'.$get_down_lagna['lagna_min'].'S'));
        
        $diff			= explode(":",$date->format('H:i:s'));

        $date1			= new DateTime($dob);
        $date1			->setTimeStamp($strtotime);
        $date1			->sub(new DateInterval('PT'.$down_deg.'H'.$down_sec.'M0S'));
        
        $diff1			= explode(":",$date1->format('H:i:s'));

        $get_up_lagna		= $diff[0]*3600+$diff[1]*60+$diff[2];
        $get_down_lagna		= $diff1[0]*3600+$diff1[1]*60+$diff1[2];
        
        $lagna_min		= explode(".",$get_up_lagna*($get_down_lagna/600));
        $lagna_sec		= explode(".",$lagna_min[1]*60/100);

        $lagna_min1		= $lagna_min[0]%60;

        $lagna_min_div		= explode(".",($lagna_min[0]/60));

        //echo $down_hour." ".$down_min."<br/>";
        //echo $lagna_min_div[0]." ".$lagna_min1." ".$lagna_sec[0];

        $calc_lagna_min		= $down_min+$lagna_min1;
        $calc_lagna_deg		= $down_hour+$lagna_min_div[0];
        if($lagna_sec[0]>=60)
        {
            $lagna_min1 = $lagna_min1+1;
        }
        while($calc_lagna_min >= 60)
        {
            $calc_lagna_min = $calc_lagna_min - 60;
            $calc_lagna_deg	= $calc_lagna_deg+1;
        }
        while($calc_lagna_deg>=30)
        {
            $calc_lagna_deg	= $calc_lagna_deg-30;
            $down_sign	= $down_sign+1;
        }
        $query          ->clear();
        $query                  ->select($db->quoteName('correction'));
        $query                  ->from($db->quoteName('#__sidereal_6'));
        $query                  ->where($db->quoteName('year').'>='.$db->quote($doy[0]));
        $db                     ->setQuery($query);
        
        $get_ayanamsha		= $db->loadAssoc();
        $ayanamsha_corr		= explode(":", $get_ayanamsha['correction']);
        if($doy[0] <= '2009')
        {
            $ayanamsha_corr_min		= $calc_lagna_min+$ayanamsha_corr[1];
            $ayanamsha_corr_deg		= $calc_lagna_deg+$ayanamsha_corr[0];
            if($ayanamsha_corr_min >= 60)
            {
                    $ayanamsha_corr_min = $ayanamsha_corr_min - 60;
                    $ayanamsha_corr_deg	= $calc_lagna_deg+1;
            }
            else if($ayanamsha_corr_deg >= 30)
            {
                    $ayanamsha_corr_deg	= $ayanamsha_corr_deg - 30;
                    $down_sign			= $down_sign + 1;
            }
            echo $down_sign." ".$ayanamsha_corr_deg." ".$ayanamsha_corr_min." ".$lagna_sec[0];
        }
        else
        {
            if($ayanamsha_corr[1] > $calc_lagna_min)
            {
                    $ayanamsha_corr_min = ($calc_lagna_min+60)-$ayanamsha_corr[1];
                    $ayanamsha_corr_deg	= $calc_lagna_deg-1;
            }
            else
            {
                    $ayanamsha_corr_min = $calc_lagna_min-$ayanamsha_corr[1];
            }
            if($ayanamsha_corr[0] > $calc_lagna_deg)
            {
                    $ayanamsha_corr_deg = ($ayanamsha_corr_deg+30)-$ayanamsha_corr[1];
                    $down_sign			= $down_sign-1;
            }
            else
            {
                    $ayanamsha_corr_deg = $ayanamsha_corr_deg-$ayanamsha_corr[1];
            }
            return "calls";
            //return $down_sign." ".$ayanamsha_corr_deg." ".$ayanamsha_corr_min." ".$lagna_sec[0];
        }
    }
}
?>