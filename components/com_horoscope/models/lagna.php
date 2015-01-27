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
    protected $siderealTime;
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
        $year           = $dob[0];
        $monthName      = date("F", mktime(0, 0, 0, $monthNum, 10));		// month in word format (ex. June/July/August)
        $leap           = date("L", mktime(0,0,$dob[2], $monthNum, $year));
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
            if(($monthName == "January" || $monthName == "February")&&($leap=="1"))
            {
                $query      ->select($db->quoteName('corr_time'));
                $query      ->from($db->quoteName('#__sidereal_2'));
                $query      ->where($db->quoteName('Year').'='.$db->quote($dob[0]).' AND '.
                                    $db->quote('leap').'='.'leap');
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
        $doy                    = explode("/",$dob);
        $tob                    = $this->tob;
        
        $date                   = new DateTime($dob);		// Datetime object with user date of birth
        $date                   ->setTimestamp($tob);
        $tob                    = $date->format('g:i:a');
        
        $tob                    = explode(":", $tob);
        
        if($tob[2]== "pm")
        {
            $date		= new DateTime($dob);		// Datetime object with user date of birth
            $date               ->setTimeStamp($siderealTime);		// time of birth for user
            $date		->format('Y-m-d H:i:s');
            $date		->add(new DateInterval('PT'.$lmt[0].'H'.$lmt[1].'M'.$lmt[2].'S'));			
        }
        else
        {
            $date		= new DateTime($dob);		// Datetime object with user date of birth
            $date		->setTimeStamp($siderealTime);		// time of birth for user
            $date		->format('Y-m-d H:i:s');
            $date		->sub(new DateInterval('PT'.$lmt[0].'H'.$lmt[1].'M'.$lmt[2].'S'));			
        }
            
        $corr_sidereal          = explode(":",$date->format('H:i:s'));
        $corr_sid_hr            = $corr_sidereal[0];
        $corr_sid_min           = $corr_sidereal[1];
        $corr_sid_sec           = $corr_sidereal[2];
        $up_min                 = ceil($corr_sid_min/4)*4;
        $down_min               = floor($corr_sid_min/4)*4;
        
        $db                     = JFactory::getDbo();  // Get db connection
        $query                  = $db->getQuery(true);
        $query1                 = $db->getQuery(true);
        $query2                 = $db->getQuery(true);
        $query3                 = $db->getQuery(true);
        $query                  = $db->getQuery($true);
        $query                  ->clear();
        $query                  = "SELECT * FROM jv_lahiri_7 WHERE latitude<='".$newlat."' AND hour='".$corr_sid_hr."' AND minute='".$up_min."' ORDER BY abs(latitude-'".$newlat."') limit 1";
        $db                     ->setQuery($query);
        
        $get_up_lagna           = $db->loadAssoc();
        $up_lagna               = $get_up_lagna['lagna_sign'];
        $up_deg                 = $get_up_lagna['lagna_degree'];
        $up_min                 = $get_up_lagna['lagna_min'];
        $up_hr                  = $get_up_lagna['hour'];
        $up_minute              = $get_up_lagna['minute'];
         
        $query1                 = "SELECT * FROM jv_lahiri_7 WHERE latitude<='".$newlat."' AND hour='".$corr_sid_hr."' AND minute='".$down_min."' ORDER BY abs(latitude-'".$newlat."') limit 1";
        $db                     ->setQuery($query1);
        $get_down_lagna		= $db->loadAssoc();
        $down_lagna             = $get_down_lagna['lagna_sign'];
        $down_deg               = $get_down_lagna['lagna_degree'];
        $down_min               = $get_down_lagna['lagna_min'];
        
        $down_hr                = $get_down_lagna['hour'];
        $down_minute            = $get_down_lagna['minute'];
        // Difference between upper value and lower value of lagna
        $diff1                  = ((($up_lagna*30*60)+($up_deg*60)+$up_min)-(($down_lagna*30*60)+($down_deg*60)+$down_min));
        //return $diff1;
        // Difference between sidereal time and lower value of lagna
        $diff2                  = (($corr_sid_hr*3600+$corr_sid_min*60+$corr_sid_sec)-($down_hr*3600+$down_minute*60));
        
        // Exact degree, minutes, seconds at sidereal time in decimal
        $diff                   = $diff1*($diff2/240);
        $diff                   = explode(".", $diff);
        $diff_sec               = ($diff[1]*60)/10;  // exact seconds
        $diff_min               = $diff[0];
        $diff_deg               = 0;
        // Convert seconds into minutes if greater then 60
        while($diff_sec>=60)
        {
            $diff_sec           = $diff_sec - 60; // substract 60 seconds 
            $diff_min           = $diff_min+1;   // add 1 minute to degree
        }
        while($diff_min>=60)
        {
            $diff_min           = $diff_min - 60;
            $diff_deg           = 0+1;
        }
        
        // Add the difference to the down value of lagna
        $lagna_acc_sec          = 0+$diff_sec;
        $lagna_acc_min          = $down_min+$diff_min;
        $lagna_acc_deg          = $down_deg+$diff_deg;
        $lagna_acc_sign         = $down_lagna;
       
       while($lagna_acc_min>=60)
       {
           $lagna_acc_min       = $lagna_acc_min-60;
           $lagna_acc_deg       = $lagna_acc_deg+1;
       }
       
       if($lagna_acc_deg>=30)
       {
           $lagna_acc_deg       = $lagna_acc_deg - 30;
           $lagna_acc_sign      = $lagna_acc_sign+1;
       }
       
        $year                   = $doy[0];

        $query3                 = "SELECT correction FROM jv_sidereal_6 WHERE year='".$year."'";
        $db                     ->setQuery($query3);
        $get_ayanamsha          = $db->loadAssoc();
        $ayanamsha_corr		= explode(":", $get_ayanamsha['correction']);
        if($year <= '2009')
        {
            $lagna_acc_min	= $lagna_acc_min+$ayanamsha_corr[1];
            $langa_acc_deg	= $lagna_acc_deg+$ayanamsha_corr[0];
            if($lagna_acc_min >= 60)
            {
                $lagna_acc_min  = $lagna_acc_min - 60;
                $lagna_acc_deg	= $lagna_acc_deg+1;
            }
            else if($lagna_acc_deg >= 30)
            {
                $lagna_acc_deg  = $ayanamsha_corr_deg - 30;
                $lagna_acc_sign	= $lagna_acc_sign + 1;
            }
            
        }
        else
        {
            if($ayanamsha_corr[1] > $lagna_acc_min)
            {
                $lagna_acc_min  = ($lagna_acc_min+60)-$ayanamsha_corr[1];
                $lagna_acc_deg  = $lagna_acc_deg-1;
            }
            else
            {
                $lagna_acc_min  = $lagna_acc_min-$ayanamsha_corr[1];
            }
            if($ayanamsha_corr[0] > $lagna_acc_deg)
            {
                $lagna_acc_deg  = ($lagna_acc_deg+30)-$ayanamsha_corr[1];
                $lagna_acc_sign		= $lagna_acc_sign-1;
            }
            else
            {
                $lagna_acc_deg  = $lagna_acc_deg-$ayanamsha_corr[1];
            }
            
        }
        $this->siderealTime     = $lagna_acc_sign.":".$lagna_acc_deg.":".$lagna_acc_min.":".$lagna_acc_sec;
        $details                = explode(":",$this->siderealTime);                 
        $lagna_acc_sign                    = $details[0];
        $gender                 = $this->gender;
        
        if($lagna_acc_sign=="0"&&$gender=="female")
        {
           $query4              = "SELECT * FROM jv_content WHERE id='127'";
        }
        else if($lagna_acc_sign=="0"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='128'";
        }
        else if($lagna_acc_sign=="1"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='103'";
        }
        else if($lagna_acc_sign=="1"&&$gender=="male")
        {
           $query4              = "SELECT * FROM jv_content WHERE id='102'";
        }
        else if($lagna_acc_sign=="2"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='104'";
        }
        else if($lagna_acc_sign=="2"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='105'";
        }
        else if($lagna_acc_sign=="3"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='106'";
        }
        else if($lagna_acc_sign=="3"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='107'";
        }
        else if($lagna_acc_sign=="4"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='108'";
        }
        else if($lagna_acc_sign=="4"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='109'";
        }
        else if($lagna_acc_sign=="5"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='110'";
        }
        else if($lagna_acc_sign=="5"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='111'";
        }
        else if($lagna_acc_sign=="6"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='114'";
        }
        else if($lagna_acc_sign=="6"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='115'";
        }
        else if($lagna_acc_sign=="7"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='116'";
        }
        else if($lagna_acc_sign=="7"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='117'";
        }
        else if($lagna_acc_sign=="8"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='118'";
        }
        else if($lagna_acc_sign=="8"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='119'";
        }
        else if($lagna_acc_sign=="9"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='120'";
        }
        else if($lagna_acc_sign=="9"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='121'";
        }
        else if($lagna_acc_sign=="10"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='123'";
        }
        else if($lagna_acc_sign=="10"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='124'";
        }
        else if($lagna_acc_sign=="11"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='125'";
        }
        else if($lagna_acc_sign=="11"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='126'";
        }
        $db                 ->setQuery($query4);
        $description        =  $db->loadAssoc();
        $count                 = count($db->loadResult());
        if($gender=="male")
        {
            $title              = "<h2>Your Lagna has ".str_replace("Lagna Males", " Rashi", $description['title'])."</h2><br/>";
        }
        else if($gender=="female")
        {
            $title              = "<h2>Your Lagna has ".str_replace("Lagna Females", " Rashi", $description['title'])."</h2><br/>";
        }
        return $title.$description['introtext'];;
        //return $description['title'];
    }
}
?>