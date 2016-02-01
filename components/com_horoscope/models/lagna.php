<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
require_once JPATH_COMPONENT.'/controller.php';
class HoroscopeModelLagna extends JModelItem
{
    protected $data;
    public function getLagna($user_details)
    {
        
        // Assigning the variables
        $fname        = $user_details['fname'];
        $gender       = $user_details['gender'];
        $dob          = $user_details['dob'];
        $tob          = $user_details['tob'];
        $lon          = $user_details['lon'];
        $lat          = $user_details['lat'];
        $tmz          = $user_details['tmz'];
        $tob          = explode(":",$tob);
        if($tob[3]=="PM")
        {
            
            $tob[0] = $tob[0]+12;
            $tob1   = strtotime($tob[0].":".$tob[1].":".$tob[2]);
        }
        else
        {
            $tob1   = strtotime($tob[0].":".$tob[1].":".$tob[2]);
        }
        $tob            = date("G:i:s",$tob1);
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query2         = $db->getQuery(true);
        $query          = "SELECT hits FROM jv_hits_counter WHERE product='calc_lagna'";
        $db             ->setQuery($query);
        $hits           = $db->loadAssoc();
        
        $hits       = (int)$hits['hits'];
        
        $hits       = $hits+1;
        
        $query2      = "UPDATE jv_hits_counter SET hits='".$hits."' WHERE product='calc_lagna'";
        $db                 ->setQuery($query2);
        $db->execute();
        $this->data  = array(
                                "fname"=>$fname,"gender"=>$gender,"dob"=>$dob,
                                "tob"=>$tob,"lon"=>$lon,"lat"=>$lat,"tmz"=>$tmz,
                            );
        $this->calculatelagna($this->data);
       
    }
    // Method to get the sidereal Time
    public function getSiderealTime($data)
    {
        $lon            = explode(":", $data['lon']);
        $dob            = explode("/",$data['dob']);
        $monthNum       = $dob[1];  // The month in number format (ex. 06 for June)
        $year           = $dob[0];
        $monthName      = date("F", mktime(0, 0, 0, $monthNum, 10));		// month in word format (ex. June/July/August)
        $leap           = date("L", mktime(0,0,$dob[2], $monthNum, $year));
        
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          -> select($db->quoteName('Sidereal'));
        $query          -> from($db->quoteName('#__lahiri_1'));
        $query          -> where($db->quoteName('Month').'='.$db->quote($monthName).'AND'.
                                 $db->quoteName('Date')."=".$db->quote($dob[2]));
        $db             ->setQuery($query);
        $count          = count($db->loadResult());
        $row            =$db->loadAssoc();
        
        if($count>0)
        {
            $get_sidetime_year                          = strtotime($row['Sidereal']);
            $date					= new DateTime($data['dob']);		// Datetime object with user date of birth
            $date					->setTimeStamp($get_sidetime_year);		// time of birth for user
   
            $query      ->clear();
            if(($monthName == "January" || $monthName == "February")&&($leap=="1"))
            {
                $query      ->select($db->quoteName('corr_time'));
                $query      ->from($db->quoteName('#__lahiri_2'));
                $query      ->where($db->quoteName('Year').'='.$db->quote($dob[0]).' AND '.
                                    $db->quote('leap').'='.'leap');
            }
            else
            {
                $query      ->select($db->quoteName('corr_time'));
                $query      ->from($db->quoteName('#__lahiri_2'));
                $query      ->where($db->quoteName('Year').'='.$db->quote($dob[0]));
                //$query_sideyear			= mysqli_query($con, "SELECT corr_time FROM jv_sidereal_2 WHERE Year='".$dob_split[0]."'");
            }
            $db                 ->setQuery($query);
            unset($count);
            $count              = count($db->loadResult());
            
            $time_diff          = $db->loadAssoc();
            $correction         = $time_diff['corr_time'];      // correction time diff using sidereal_2 table
            //echo $correction;exit;
            $corr_diff          = substr($correction, 0,1);     // the positive/negative sign
            $corr_time		= substr($correction,1);        // the time diff in mm:ss format
            $diff               = explode(":", $corr_time);
            
            if($corr_diff == "-")
            {
                //echo $corr_diff;exit;
                //$get_sidereal_timediff	= 
                if($diff[0] != "00"||$diff[0] != "0")
                {
                    $date	->sub(new DateInterval('PT'.$diff[0].'M'.$diff[1].'S'));
                    //echo $date->format('H:i:s');
                }
                else
                {
                    $date	->sub(new DateInterval('PT'.$diff[1].'S'));
                    //echo $date->format('H:i:s');
                }
               
            }
            else if($corr_diff == "+")
            {
                //echo $corr_diff;exit;
                //echo $diff[0].":".$diff[1];exit;
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
                //echo $sid_corr['corr_sign'];exit;
                    //$get_sidereal_timediff	= 
                //$date           ->sub(new DateInterval('PT'.$corr_mins.'M'.$corr_secs.'S'));
                flush($diff);
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
            else if($sid_corr['corr_sign'] == "+")
            {
                //$get_sidereal_timediff
                //echo $corr_diff['st_correction'];echo "calls";exit;
                flush($diff);
                $diff	= explode(".",$sid_corr['st_correction']);
                //echo $date->format('H:i:s');exit;
                if($diff[0] != "00"||$diff[0] != "0")
                {
                    echo "calls1";exit;
                    $date   ->add(new DateInterval('PT'.$diff[0].'M'.$diff[1].'S'));
                }
                else
                {
                    $date   ->add(new DateInterval('PT'.$diff[1].'S'));
                }
            }
            return $date->format('H:i:s');;
        }
        
        //longitude >= '".($lon)."'
    }
    public function getLmt($data)
    {
        //print_r($data);exit;
        $lon        = explode(":", $data['lon']);
        $lat        = explode(":", $data['lat']);
        $gmt        = "GMT".$data['tmz'];
        $dob        = $data['dob'];
        $tob        = $data['tob'];

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
            //echo $new_diff;exit;
            // Computation to check Sidereal Time
            $date		= new DateTime($dob);		// Datetime object with user date of birth
            $date		->setTimeStamp($tob);		// time of birth for user
            $tob_format		= $date->format('g:i a');
            if(strpos($tob_format, pm))
            {
                $date           ->add(new DateInterval('PT12H0M0S'));
            }
            
            $diff		= explode(":",$new_diff);
            if($std_lon_min > $loc_lon_min)
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
            //echo $date->format('H:i:s');exit;
            $am_pm                      = explode(":",$date->format('H:i:s'));
            if(strpos($tob_format,"am"))
            {
                $dateObject             = new DateTime($dob);
                $dateObject             ->setTimestamp(strtotime('12:00:00'));
                $dateObject             ->sub(new DateInterval('PT'.$am_pm[0].'H'.$am_pm[1].'M'.$am_pm[2].'S'));
            }
            else
            {
                $dateObject             = new DateTime($dob);
                $dateObject             ->setTimestamp(strtotime($am_pm[0].":".$am_pm[1].":".$am_pm[2]));
            }
            return $dateObject->format('H:i:s');
        }
        else
        {
            return "No matching results";
        }
    }
    public function calculatelagna($data)
    {
        //print_r($data);
        $lat                    = explode(":",$data['lat']);
        $lat                    = $lat[0].'.'.$lat[1];
        //$gender                 = $data['gender'];
        //echo $this->getSiderealTime()."<br/>";
        //echo $this->getLmt();exit;
        $sidtime		= strtotime($this->getSiderealTime($data));
       	$lmt			= explode(":",$this->getLmt($data));
        $dob                    = $data['dob'];
        //return $dob;
        $doy                    = explode("/",$dob);
        $tob                    = strtotime($data['tob']);
        
        $date    		= new DateTime($dob);
        $date                   ->setTimestamp($tob);
        $tob_format		= $date->format('g:i a');
        
        if(strpos($tob_format,"pm"))
        {
            $dateObject		= new DateTime($dob);		// Datetime object with user date of birth
            $dateObject		->setTimeStamp($sidtime);		// time of birth for user
            $dateObject		->add(new DateInterval('PT'.$lmt[0].'H'.$lmt[1].'M'.$lmt[2].'S'));
      
        }
        else
        {
            $dateObject		= new DateTime($dob);		// Datetime object with user date of birth
            $dateObject		->setTimeStamp($sidtime);		// time of birth for user
            $dateObject		->sub(new DateInterval('PT'.$lmt[0].'H'.$lmt[1].'M'.$lmt[2].'S'));			
        }
        //echo $dateObject->format('G:i:s');exit;
        //echo $dat_hr.":".$dat_min.":".$dat_sec;exit;
        //echo $date->format('H:i:s');exit;
        $dat_hr                 = explode(":",$dateObject->format('G:i:s'));
        $corr_sid_hr            = $dat_hr[0];
        $corr_sid_min           = $dat_hr[1];
        $corr_sid_sec           = $dat_hr[2];
        
        if($corr_sid_min%4 =="0")
        {
            $up_min             = $corr_sid_min;
            $down_min           = $corr_sid_min-4;
        }
        else
        {
            $up_min                 = ceil($corr_sid_min/4)*4;
            $down_min               = floor($corr_sid_min/4)*4;
        }
        $db                     =   JFactory::getDbo();  // Get db connection
        $query                  =   $db->getQuery(true);
        $query1                 =   $db->getQuery(true);
        //$query2                 =   $db->getQuery(true);
        //$query3                 =   $db->getQuery(true);
        //$query4                 =   $db->getQuery(true);
        //$query5                 =   $db->getQuery(true);
        $query                  ->clear();
        
        $query                  = "SELECT * FROM jv_lahiri_7 WHERE latitude<='".$lat."' AND hour='".$corr_sid_hr."' AND minute='".$up_min."' ORDER BY abs(latitude-'".$lat."') limit 1";
        $db                     ->setQuery($query);
       
        $get_up_lagna           = $db->loadAssoc();
        $up_lagna               = $get_up_lagna['lagna_sign'];
        $up_deg                 = $get_up_lagna['lagna_degree'];
        $up_min                 = $get_up_lagna['lagna_min'];
        $up_hr                  = $get_up_lagna['hour'];
        $up_minute              = $get_up_lagna['minute'];
        //echo $lat.":".$corr_sid_hr.":".$down_min;exit;
        //return $up_lagna.":".$up_deg.":".$up_min;
        $query                  = "SELECT * FROM jv_lahiri_7 WHERE latitude<='".$lat."' AND hour='".$corr_sid_hr."' AND minute='".$down_min."' ORDER BY abs(latitude-'".$lat."') limit 1";
        $db                     ->setQuery($query);
        //$count                  = count($db->loadResult());
        $get_down_lagna		= $db->loadAssoc();
        $down_lagna             = $get_down_lagna['lagna_sign'];  // lagna sign
        $down_deg               = $get_down_lagna['lagna_degree'];  // lagna degree
        $down_min               = $get_down_lagna['lagna_min'];   // lagna minutes
        $down_hr                = $get_down_lagna['hour'];   // time in hours
        $down_minute            = $get_down_lagna['minute'];   // time in minutes
        //return $down_lagna.":".$down_deg.":".$down_min;
        // Difference between upper value and lower value of lagna
        $diff1                  = ((($up_lagna*30*60)+($up_deg*60)+$up_min)-(($down_lagna*30*60)+($down_deg*60)+$down_min));
        
        //return $diff1;
        // Difference between sidereal time and lower value of lagna
        $diff2                  = (($corr_sid_hr*3600+$corr_sid_min*60+$corr_sid_sec)-($down_hr*3600+$down_minute*60));
        // Exact degree, minutes, seconds at sidereal time in decimal
        $diff                   = round($diff1*($diff2/240),2);
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
        
        //return $lagna_acc_sign.":".$lagna_acc_deg.":".$lagna_acc_min.":".$lagna_acc_sec;
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
       //echo $lagna_acc_sign.":".$lagna_acc_deg.":".$lagna_acc_min.":".$lagna_acc_sec;exit;
        $year                   = $doy[0];
        $query1                  ->clear();
        $query1                  = "SELECT correction FROM jv_lahiri_5 WHERE Year='".$year."'";
        $db                     ->setQuery($query1);
        $count                  = count($db->loadResult());
       
        $get_ayanamsha          = $db->loadAssoc();
        //echo $get_ayanamsha['correction'];exit;
        $ayanamsha_corr		= explode(":", $get_ayanamsha['correction']);
        $ayanamsha_corr[0]      = substr($ayanamsha_corr[0], 1);
        //echo $lagna_acc_sign." ".$lagna_acc_deg." ".$lagna_acc_min." ".$lagna_acc_sec;exit;
        //echo $ayanamsha_corr[0].":".$ayanamsha_corr[1];exit;
        if($year <= '1938')
        {
            $lagna_acc_min	= $lagna_acc_min+$ayanamsha_corr[1];
            $lagna_acc_deg	= $lagna_acc_deg+$ayanamsha_corr[0];
            if($lagna_acc_min >= 60)
            {
                $lagna_acc_min  = $lagna_acc_min - 60;
                $lagna_acc_deg	= $lagna_acc_deg+1;
            }
            else if($lagna_acc_deg >= 30)
            {
                $lagna_acc_deg  = $lagna_acc_deg - 30;
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
                $lagna_acc_deg  = ($lagna_acc_deg+30)-$ayanamsha_corr[0];
                $lagna_acc_sign = $lagna_acc_sign-1;
            }
            else
            {
                $lagna_acc_deg  = $lagna_acc_deg-$ayanamsha_corr[0];

            }
            
        }
        //$data            = array("name"=>$this->fname,"gender"=>$this->gender,
                                       
        $lagna           = array("sign"=>$lagna_acc_sign,"degree"=>$lagna_acc_deg,
                                        "min"=>$lagna_acc_min,"sec"=>$lagna_acc_sec);
        //print_r($lagna);exit;
        $data            = array_merge($data, $lagna);
        //print_r($data);exit;
        //return $data;
        $this->getMoonData($data);
        //echo $lagna_acc_sign." ".$lagna_acc_deg." ".$lagna_acc_min." ".$lagna_acc_sec;exit;
        //$this->getData($data);
        //$app        = &JFactory::getApplication();
        //$app        ->redirect(JUri::base().'calculate-lagna'); 
        /*if($lagna_acc_sign=="0"&&$gender=="female")
        {
           $query4              = "SELECT * FROM jv_content WHERE id='103'";
        }
        else if($lagna_acc_sign=="0"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='102'";
        }
        else if($lagna_acc_sign=="1"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='104'";
        }
        else if($lagna_acc_sign=="1"&&$gender=="male")
        {
           $query4              = "SELECT * FROM jv_content WHERE id='105'";
        }
        else if($lagna_acc_sign=="2"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='106'";
        }
        else if($lagna_acc_sign=="2"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='107'";
        }
        else if($lagna_acc_sign=="3"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='108'";
        }
        else if($lagna_acc_sign=="3"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='109'";
        }
        else if($lagna_acc_sign=="4"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='110'";
        }
        else if($lagna_acc_sign=="4"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='111'";
        }
        else if($lagna_acc_sign=="5"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='114'";
        }
        else if($lagna_acc_sign=="5"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='115'";
        }
        else if($lagna_acc_sign=="6"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='116'";
        }
        else if($lagna_acc_sign=="6"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='117'";
        }
        else if($lagna_acc_sign=="7"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='118'";
        }
        else if($lagna_acc_sign=="7"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='119'";
        }
        else if($lagna_acc_sign=="8"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='120'";
        }
        else if($lagna_acc_sign=="8"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='121'";
        }
        else if($lagna_acc_sign=="9"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='123'";
        }
        else if($lagna_acc_sign=="9"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='124'";
        }
        else if($lagna_acc_sign=="10"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='125'";
        }
        else if($lagna_acc_sign=="10"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='126'";
        }
        else if($lagna_acc_sign=="11"&&$gender=="female")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='127'";
        }
        else if($lagna_acc_sign=="11"&&$gender=="male")
        {
            $query4              = "SELECT * FROM jv_content WHERE id='128'";
        }
         $db                     ->setQuery($query4);
         $lagna                 = $db->loadAssoc();
         if($gender=="male")
         {
            echo "<h2>Your Lagna is ".str_replace("Lagna Males","",$lagna['title'])."</h2>";
         }
         else if($gender=="female")
         {
            echo "<h2>Your Lagna is ".str_replace("Lagna Females","",$lagna['title'])."</h2>"; 
         }*/
         ?>
    <div class="lagna_find" id="<?php echo $lagna['id']; ?>"></div>
    <?php
    //echo "<br/>";
    //echo $lagna['introtext'];

    }
    protected function getMoonData($data)
    {
        //print_r($data);
        $dob        = explode("/",$data['dob']);
        $year       = (int)$dob[0];
        $month      = (int)$dob[1];
        $day        = (int)$dob[2];
        if($year < 2000)
        {
            $db         = JFactory::getDbo();
            $query      = $db->getQuery(true);
            $query      ->select($db->quoteName(array('yob','moon')));
            $query      ->from($db->quoteName('#__raman_moon2000'));
            $query      ->where($db->quoteName('year').'='.$db->quote($year).
                           'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                           $db->quoteName('day').'<='.$db->quote($day));
            $query      ->order($db->quoteName('day').' desc');
            $query      ->setLimit('1');
            $db->setQuery($query);
            $row            = $db->loadAssoc();
            $down_yob       = $row['yob'];
            
            $down_moon      = explode(".",$row['moon']);
            $query          ->clear();
            $query          ->select($db->quoteName(array('yob','moon')));
            $query          ->from($db->quoteName('#__raman_moon2000'));
            $query          ->where($db->quoteName('year').'='.$db->quote($year).
                                'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                                $db->quoteName('day').'>'.$db->quote($day));
            $query          ->order($db->quoteName('day').' asc');
            $query          ->setLimit('1');
            $db->setQuery($query);
            flush($row);
            $row                = $db->loadAssoc();
            $up_yob             = $row['yob'];
            $up_moon            = explode(".",$row['moon']);
            
            $datetime1          = new DateTime($down_yob);
            $datetime2          = new DateTime($up_yob);
            $interval           = $datetime1->diff($datetime2);
            $intval             = (int)$interval->format('%a');
            
            //echo $intval."<br/>";
            $up_mov             = ($up_moon[0]*60*4)+($up_moon[1]*4);
            $down_mov           = ($down_moon[0]*60*4)+($down_moon[1]*4);
            
            if($up_mov  > $down_mov)
            {
                $one_day_transit    = ($up_mov-$down_mov)/$intval;
            }
            else
            {
                $one_day_transit    = (($up_mov+(360*60*4))-$down_mov)/$intval;
            }
            $total_transit      = $down_mov+$one_day_transit;
            
            $date               = new DateTime($data['dob']);
            $date               ->setTimeStamp(strtotime($data['tob']));
            $date1              = new DateTime($data['dob']);
            $date1              ->setTime('12','00','00');
            $tmz                = explode(":", $data['tmz']);
            $sign               = substr($tmz[0],0,1);
            $tmz_hr             = substr($tmz[0], 1);
            if($sign == "+")
            {
                $date1          ->add(new DateInterval('PT'.$tmz_hr.'H'.$tmz[1].'M0S'));
            }
            else if($sign == "-")
            {
                $date1          ->sub(new DateInterval('PT'.$tmz_hr.'H'.$tmz[1].'M0S'));
            }
            
            $tob_str                = strtotime($date->format('G:i:s'));    // strtotime of time of birth for comparision
            $gmt_str                = strtotime($date1->format('G:i:s'));   // strtotime of local time for comparision
            if($tob_str > $gmt_str)
            {
                $date           ->setTimestamp($tob_str);
                $gmt            = explode(":",date('G:i:s', $gmt_str));
                $date           ->sub(new DateInterval('PT'.$gmt[0].'H'.$gmt[1].'M'.$gmt[2].'S'));
                //echo $date      ->format('G:i:s');
            }
            else if($tob <= $gmt)
            {
               
                $date           ->setTimestamp($gmt_str);
                $tob            = explode(":",date('G:i:s', $tob_str));
                $date           ->sub(new DateInterval('PT'.$tob[0].'H'.$tob[1].'M'.$tob[2].'S'));
                //echo $date      ->format('G:i:s');
            }
            //echo $one_day_transit/(4*60);
            $time_diff          = explode(":",$date->format("G:i:s"));
            $time_diff          = $time_diff[0]*3600+$time_diff[1]*60+$time_diff[2];
            $hr_transit         = round(($one_day_transit*$time_diff/(24*3600)),2);
            
            if($tob_str > $gmt_str)
            {
                $actual_transit    = $total_transit+$hr_transit;
            }
            else
            {
                $actual_transit    = $total_transit-$hr_transit;
            }
            //echo $actual_transit/(4*60);
            //$actual_transit         = round($actual_transit/(4*60),2);
            $date1  = null;
            unset($date1);
            $query                  ->clear();
            $query                  ->select($db->quoteName('ayanamsha'))
                                    ->from($db->quoteName('#__raman_ayanamsha'))
                                    ->where($db->quoteName('year').'='.$db->quote($dob[0]));
            $db->setQuery($query);
            $result                 = $db->loadAssoc();
            $ayanamsha              = explode(".",$result['ayanamsha']);
            $actual_transit         = $actual_transit - (($ayanamsha[0]*60*4)+($ayanamsha[1]*4));
            $actual_transit         = $actual_transit/(4*60);
            $convert_transit        = explode(".", $actual_transit);
            $deg                    = $convert_transit[0];
            $min                    = ($actual_transit-$deg)*60;
            $sec                    = ($actual_transit-$deg-($min/60))*3600;
            $moon                   = $deg.":".round($min,0).":".round($sec,0);
            $moon                   = array("moon"=>$moon);
        //print_r($lagna);exit;*/
            
        }
        $data            = array_merge($data, $moon);
        print_r($data);
        //$this->calculateSun($data);
    }
    protected function calculateSun($data)
    {
        
    }
}
?>