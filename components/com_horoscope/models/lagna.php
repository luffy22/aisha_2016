<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
require_once JPATH_COMPONENT.'/controller.php';
class HoroscopeModelLagna extends JModelItem
{
    public $data;
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
            $tob[0] = (int)$tob[0]+12;
            $tob   = $tob[0].":".$tob[1].":".$tob[2];
        }
        else
        {
            $tob   = $tob[0].":".$tob[1].":".$tob[2];
        }
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query2         = $db->getQuery(true);
        $query          = "SELECT hits FROM jv_hits_counter WHERE product='calc_lagna'";
        $db             ->setQuery($query);
        $hits           = $db->loadAssoc();
        $hits           = (int)$hits['hits'];
        $hits           = $hits+1;
        
        $query2      = "UPDATE jv_hits_counter SET hits='".$hits."' WHERE product='calc_lagna'";
        $db                 ->setQuery($query2);
        $db->execute();
        $gmt            = "12:00:00";
        $gmt            = $this->getGMT($gmt, $tmz);
      
        $tob_str        = strtotime($tob);
        $gmt_str        = strtotime($gmt);
        if($tob_str>$gmt_str)
        {
            $diff       = "+".$this->getAddSubTime($dob,$tob_str,$gmt_str,"-");
        }
        else if($gmt_str>$tob_str)
        {
            $diff       = "-".$this->getAddSubTime($dob,$gmt_str,$tob_str,"-");
        }
        
        /* 
        *  @param fullname, gender, date of birth, time of birth, 
        *  @param longitude, latitude, timezone and
        *  @param timezone in hours:minutes:seconds format
        */ 
     
        $this->data  = array(
                                "fname"=>$fname,"gender"=>$gender,"dob"=>$dob,
                                "tob"=>$tob,"lon"=>$lon,"lat"=>$lat,"tmz"=>$tmz,
                                "tmz_hr"=>$gmt,"time_diff"=>$diff
                            );
        //print_r($this->data);exit;
        $this->calculatelagna($this->data);  
    }
    /*
     *  get the local time for example India= 17:30:00, London= 12:00:00
     * @param $val1 $val1 is time in hh:mm:dd format from which
     *  other value is to added or substracted
     * @param $val2 $val2 is time in hh:mm:dd format 
     * along with + or - sign which requires to be added or
     * subtracted from $value1
     */
    
    public function getGMT($val1, $val2)
    {
        $sign           = substr($val2,0,1);
        $val1           = explode(":",$val1);
        $val2           = explode(":",substr($val2,1));
        if($sign == "-")
        {
            if($val1[1]<$val2[1])
            {
                $tmz_min    = $val2[1]-$val1[1];
                $tmz_hr     = ($val1[0]-$val2[0])-1;
            }
            else
            {
                $tmz_min    = $val1[1]-$val2[1];
                $tmz_hr     = $val1[0]-$val2[0];                
            }
        }
        else
        {
            $tmz_min    = $val1[1]+$val2[1];
            $tmz_hr     = $val1[0]+$val2[0];       
        }
        unset($val1);
        unset($val2);
        return $tmz_hr.":".$tmz_min.":00";
    }
    // function checks seconds, minutes and degrees 
    // seconds and mins less then 60 and adding to degrees
    public function convertDegMinSec($deg,$min,$sec)
    {
        while($sec>=60)
        {
            $sec    = $sec-60;
            $min    = $min+1; 
        }
        while($min>=60)
        {
            $min    = $min-60;
            $deg    = $deg+1;
        }
        
        return $deg.":".$min.":".$sec;
    }
    // getting the differential transit when only hours and 
    // minutes are specified. Return value in Degree, Hours and Minute Format.
    public function getDiffTransit($hr,$min ,$intval, $intval2)
    {
        $transit    = ($hr*60*4)+($min*4);
        $intval     = $intval;
        $intval2    = $intval2;
        $transit    = round((($transit*$intval2)/$intval),2);
        $value      = $this->convertDecimalToDegree($transit);
        
        return $value;
    }
    // This one is for values which are already described in seconds
    // without converting values into seconds
    public function getDiffTransit2($val1, $val2, $intval)
    {
        $transit    = abs((($val1*$val2)/$intval));
        $value      = $this->convertDecimalToDegree($transit);
        return $value;
    }
    // converting decimal to degree for example 12.22 = 12 deg 22 min 30 sec
    public function convertDecimalToDegree($decimal)
    {
        $deg        = abs(($decimal/(60*4)));
        $real_deg   = explode(".",$deg);
        $min        = intval(($deg-$real_deg[0])*60);
        $real_min   = explode(".", $min);
        $sec        = intval(($deg-$real_deg[0]-($min/60))*3600);
        $real_sec   = explode(".", $sec);
        return $real_deg[0].":".$real_min[0].":".$real_sec[0];
    }
    // adding degree, minutes seconds
    public function addDegMinSec($deg1,$min1,$sec1,$deg2,$min2,$sec2)
    {
        $deg        = $deg1+$deg2;
        $min        = $min1+$min2;
        $sec        = $sec1+$sec2;
        $value      = $this->convertDegMinSec($deg,$min,$sec);
        return $value;
    }
    // subtracting degree minutes seconds
    public function subDegMinSec($deg1,$min1,$sec1,$deg2,$min2,$sec2)
    {
        if($deg1>$deg2)
        {
            $deg            = $deg1-$deg2;
            if($min2>$min1)
            {
                $deg        = $deg-1;
                $min        = ($min1+60)-$min2;
            }
            else
            {
                $min        = $min1-$min2;
            }
            if($sec2>$sec1)
            {
                $min        = $min-1;
                $sec        = ($sec+60)-$sec2;
            }
            else
            {
                $sec        = $sec-$sec2;
            }
        }
        else if($deg1<$deg2)
        {
            $deg        = $deg2-$deg1;
            if($min2>$min1)
            {
                $deg        = $deg-1;
                $min        = ($min1+60)-$min2;
            }
            else
            {
                $min        = $min1-$min2;
            }
            if($sec2>$sec1)
            {
                $min        = $min-1;
                $sec        = ($sec+60)-$sec2;
            }
            else
            {
                $sec        = $sec-$sec2;
            }
        }
        $value      = $this->convertDegMinSec($deg,$min,$sec);
        return $value;
    }
    // divinding degree, minute and second by divisor
    public function divideDegMinSec($deg,$min,$sec,$divisor)
    {
        $new_deg        = intval($deg/$divisor);
        $deg_mod        = $deg%$divisor;
        $new_min        = $min+($deg_mod*60);
        $new_min        = intval($new_min/$divisor);
        $min_mod        = $min%$divisor;
        $sec            = $sec+($min_mod*60);
        $new_sec        = intval($sec/$divisor);
        
        $value          = $this->convertDegMinSec($new_deg, $new_min, $new_sec);
        return $value;
    }
    public function getAddSubTime($date,$val1,$val2,$sign)
    {
        $val2           = explode(":",date('G:i:s',$val2));
        $date           = new DateTime($date);
        $date           ->setTimestamp($val1);
        if($sign=="-")
        {
            $date           ->sub(new DateInterval('PT'.$val2[0].'H'.$val2[1].'M'.$val2[2].'S'));
        }
        else if($sign=="+")
        {
            $date           ->add(new DateInterval('PT'.$val2[0].'H'.$val2[1].'M'.$val2[2].'S'));
        }
        return $date->format('H:i:s');
    }
    public function convertDegMinToSec($deg,$min)
    {
        $sec        = ($deg*60*4)+($min*4);
        return $sec;
    }
    // Method to get the sidereal Time
    public function getSiderealTime($data)
    {
        //print_r($data);exit;
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
        //echo $row['Sidereal'];exit;
        if($count>0)
        {
            $get_sidetime_year      = strtotime($row['Sidereal']);
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
            $corr_diff          = substr($correction, 0,1);     // the positive/negative sign
            $corr_time		= substr($correction,1);        // the time diff in mm:ss format
            $corr_time          = strtotime("00:".$corr_time);        // corr_time string_to_time    
            $sid_time           = strtotime($this->getAddSubTime($data['dob'],$get_sidetime_year ,$corr_time,$corr_diff));
            //echo date('G:i:s',$sid_time);exit;
            $query              ->clear();
            $query              = "select corr_sign, st_correction FROM jv_sidereal_3 WHERE longitude >= '".($lon[0].'.'.$lon[1])."'
                                    order by abs(longitude - '".($lon[0].'.'.$lon[1])."') limit 1";
            $db                 ->setQuery($query);
            unset($count);
            $count              = count($db->loadResult());
            $sid_corr           = $db->loadAssoc();     // sidereal correction in seconds
            $corr_time          = str_replace(".",":",$sid_corr['st_correction']);
            $sign               = $sid_corr['corr_sign'];
            $corr_time          = strtotime("00:".$corr_time);
            $sidereal           = $this->getAddSubTime($data['dob'],$sid_time,$corr_time,$sign);           
        }
        return $sidereal;
        //longitude >= '".($lon)."'
    }
    public function getLmt($data)
    {
        //print_r($data);exit;
        $lon        = explode(":", $data['lon']);
        $lat        = explode(":", $data['lat']);
        $gmt        = "GMT".$data['tmz'];
        $dob        = $data['dob'];
        $tob        = strtotime($data['tob']);
        
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
            
            $std_lon_sec	= $this->convertDegMinToSec($std_meridian[0], $std_meridian[1]);		// convert minutes into seconds & multiply by 4 then add to seconds multiplied by 4
            $loc_lon_sec	= $this->convertDegMinToSec($lon[0],$lon[1]);			// convert minutes into seconds & multiply by 4 then add to seconds multiplied by 4
            if($std_lon_sec > $loc_lon_sec)
            {
                
                $new_diff	= $std_lon_sec - $loc_lon_sec;
                //$new_diff	= gmdate('H:i:s', $new_diff);
                $diff           = strtotime(gmdate("G:i:s",$new_diff));     // gmdate is used for value below 24 hr
                $date           = strtotime($this->getAddSubTime($dob,$tob,$diff,"-"));
            }
            else
            {
                $new_diff	= $loc_lon_sec	- $std_lon_sec;
                //$new_diff	= gmdate('H:i:s', $new_diff);
                $diff           = strtotime(gmdate("G:i:s",$new_diff));     // gmdate is used for value below 24 hr
                $date           = strtotime($this->getAddSubTime($dob,$tob,$diff,"+"));
            }
            //echo $new_diff;exit;
            $dateObject		= new DateTime($dob);		// Datetime object with user date of birth
            $dateObject		->setTimeStamp($date);		// time of birth for user
            $tob_format		= $dateObject->format('g:i a');
            $noon_time          = strtotime('12:00:00');
            if(strpos($tob_format, am))
            {
                // if lmt is am then subtract that time from 12 at noon
                $date           = $this->getAddSubTime($dob,$noon_time,$date,"-");
            }
            else
            {
                $date           = $dateObject->format('G:i:s');
            }
            //echo $date;exit;
            $dateObject         = (strtotime($date));
            $lmt                = explode(":",$date);
            $lmt_hr             = $lmt[0];
            $lmt_min            = $lmt[1];
            $lmt_sec            = $lmt[2];
            //$lmt                = $lmt_hr*3600+$lmt_min*60+$lmt_sec;
            $query                  ->clear();
            $query                  ->select($db->quoteName('min'));
            $query                  ->from($db->quoteName('#__sidereal_4'));
            $query                  ->where($db->quoteName('hour').'='.$db->quote($lmt_hr));
            $db                     ->setQuery($query);
            $result                 = $db->loadAssoc();
            //$count                  = count($result);
            //echo $result['min'];exit;
            $min                    = strtotime("00:".$result['min']);
            $lmt                    = strtotime($this->getAddSubTime($dob,$dateObject,$min,"+"));
            $query                  ->clear();
            $query                  ->select($db->quoteName('diff'));
            $query                  ->from($db->quoteName('#__sidereal_5'));
            $query                  ->where($db->quoteName('min').'>='.$db->quote($lmt_min));
            $db                     ->setQuery($query);
            unset($result);
            $result                 = $db->loadAssoc();
            $diff                   = "00:".$result['diff'];
            $sec                    = strtotime($diff);
            $date                   = strtotime($this->getAddSubTime($dob,$lmt,$sec,"+"));
            $date                   = date('g:i:s',$lmt);
            return $date;
        }
        else
        {
            return "No matching results";
        }
    }
    public function calculatelagna($data)
    {
        //print_r($data);exit;
        $lat            = explode(":",$data['lat']);
        $dir            = $lat[2];
        $lat            = $lat[0].'.'.$lat[1];

        //echo $this->getSiderealTime($data)."<br/>";
        //echo $this->getLmt($data);exit;
        $sidtime        = strtotime($this->getSiderealTime($data));
       	$lmt            = strtotime($this->getLmt($data));
        $dob            = $data['dob'];
        //return $dob;
        $doy            = explode("/",$dob);
        $tob            = strtotime($data['tob']);
        
        $date           = new DateTime($dob);
        $date           ->setTimestamp($tob);
        $tob_format     = $date->format('g:i a');
        if(strpos($tob_format,"pm"))
        {
            $dateObject = $this->getAddSubTime($dob,$sidtime,$lmt,"+");
        }
        else
        {
            $dateObject = $this->getAddSubTime($dob,$sidtime,$lmt,"-");
        }
        if($dir == "S")
        {
            $noon           = strtotime('12:00:00');
            $date           = strtotime($dateObject);
            $dateObject     = $this->getAddSubTime($dob, $date, $noon, "+");
        }
        else
        {
            $dateObject     = $dateObject;
        }
        //echo $dateObject;exit;
        $dat_hr         = explode(":",$dateObject);
        $corr_sid_hr    = $dat_hr[0];
        $corr_sid_min   = $dat_hr[1];
        $corr_sid_sec   = $dat_hr[2];
        
        if($corr_sid_min%4 =="0")
        {
            $up_min     = $corr_sid_min+4;
            $down_min   = $corr_sid_min;
        }
        else
        {
            $up_min     = ceil($corr_sid_min/4)*4;
            $down_min   = floor($corr_sid_min/4)*4;
        }
        if($up_min=="60")
        {
            $corr_sid_hr    = $corr_sid_hr+1;
            $up_min         = $up_min-60;
        }
        //echo $up_min.":".$down_min;exit;
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          ->select($db->quoteName(array('lagna_sign','lagna_degree','lagna_min')));
        $query          ->from($db->quoteName('#__lahiri_7'));
        $query          ->where($db->quoteName('latitude').'<='.$db->quote($lat).
                                'AND'.$db->quoteName('hour').'='.$db->quote($corr_sid_hr).'AND'.
                                 $db->quoteName('minute').'='.$db->quote($up_min));
        $query          ->order($db->quoteName('latitude').' desc');
        $query          ->setLimit('1');
        $db             ->setQuery($query);
       
        $up_lagna       = $db->loadAssoc();
        $up_sign        = $up_lagna['lagna_sign'];
        $up_deg         = $up_lagna['lagna_degree'];
        $up_min         = $up_lagna['lagna_min'];
        //echo $up_sign.":".$up_deg.":".$up_min."<br/>";
        $query          ->clear();
        $query          ->select($db->quoteName(array('lagna_sign','lagna_degree','lagna_min','hour','minute')));
        $query          ->from($db->quoteName('#__lahiri_7'));
        $query          ->where($db->quoteName('latitude').'<='.$db->quote($lat).
                                'AND'.$db->quoteName('hour').'='.$db->quote($corr_sid_hr).'AND'.
                                 $db->quoteName('minute').'='.$db->quote($down_min));
        $query          ->order($db->quoteName('latitude').' desc');
        $query          ->setLimit('1');
        $db             ->setQuery($query);
        $down_lagna     = $db->loadAssoc();
        $down_sign      = $down_lagna['lagna_sign'];
        $down_deg       = $down_lagna['lagna_degree'];
        $down_min       = $down_lagna['lagna_min'];
        $down_hr        = $down_lagna['hour'];
        $down_min       = $down_lagna['minute'];
        //echo $down_sign.":".$down_deg.":".$down_min;exit;
        // Difference between upper value and lower value of lagna
        $diff1                  = ((($up_sign*30*60)+($up_deg*60)+$up_min)-(($down_sign*30*60)+($down_deg*60)+$down_min));
        //echo $diff1;exit;
        $diff2                  = ((($corr_sid_hr*3600)+($corr_sid_min*60)+($corr_sid_sec))-(($down_hr*3600)+($down_min*60)));
        //echo $diff2;exit;
        // Exact degree, minutes, seconds at sidereal time in decimal
        $diff                   = round((($diff1*$diff2)/240),2);
        $diff                   = explode(":",$this->convertDecimalToDegree($diff));
        $diff                   = explode(":",$this->addDegMinSec($down_deg,$down_min,0,$diff[0],$diff[1],$diff[2]));
        //$diff                   = $this->convertSignDegMinSec($down_sign,$diff[0],$diff[1],$diff[2]);
        
       //echo $lagna_acc_sign.":".$lagna_acc_deg.":".$lagna_acc_min.":".$lagna_acc_sec;exit;
        $year                   = $doy[0];
        //echo $year;exit;
        $query                  ->clear();
        $query                  = "SELECT correction FROM jv_lahiri_5 WHERE Year='".$year."'";
        $db                     ->setQuery($query);
        $count                  = count($db->loadResult());
        
        $get_ayanamsha          = $db->loadAssoc();
        $ayanamsha_corr		= explode(":", $get_ayanamsha['correction']);
        $sign                   = substr($get_ayanamsha['correction'],0, 1);
        $diff[0]                = ($down_sign*30)+$diff[0];
        //echo $diff[0];exit;
        
        if($sign=="-")
        {
            $lagna              = $this->subDegMinSec($diff[0],$diff[1],$diff[2],$ayanamsha_corr[0],$ayanamsha_corr[1],0);
        }
        else
        {
            $lagna              = $this->addDegMinSec($diff[0],$diff[1],$diff[2],$ayanamsha_corr[0],$ayanamsha_corr[1],0);
        }
        //echo $lagna_acc_sign." ".$lagna_acc_deg." ".$lagna_acc_min." ".$lagna_acc_sec;exit;
        //echo $lagna;exit;
        $lagna_sign         = explode(":",$lagna);
        //$data            = array("name"=>$this->fname,"gender"=>$this->gender,
        if($dir == "S")
        {
            $lagna_sign[0]              = $lagna_sign[0]+180;
            if($lagna_sign[0]>360)
            {
                $lagna_sign[0]          = $lagna_sign[0]-360;
            }
            $lagna              = $lagna_sign[0].":".$lagna_sign[1].":".$lagna_sign[2];
        } 
        else
        {
            if($lagna_sign[0]>360)
            {
                $lagna_sign[0]          = $lagna_sign[0]-360;
            }
            $lagna              = $lagna_sign[0].":".$lagna_sign[1].":".$lagna_sign[2];
        }
        $lagna           = array("lagna"=>$lagna);
        //print_r($lagna);exit;
        $data            = array_merge($data, $lagna);
        //print_r($data);exit;
        $this->getMoonData($data);
    }
    protected function getMoonData($data)
    {
        //print_r($data);exit;
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
            
            $row            = $db->loadAssoc();
            $down_yob       = $row['yob'];

            $down_moon      = explode(".",$row['moon']);
            //echo $down_moon[0].":".$down_moon[1];exit;
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
            $datetime3          = new DateTime($data['dob']);
            $interval           = $datetime1->diff($datetime2);
            $interval2          = $datetime1->diff($datetime3);
            $intval             = (int)$interval->format('%a');
            $intval2            = (int)$interval2->format('%a');
            //echo $intval;exit;
            //echo $up_moon[0].":".$up_moon[1]." vs ".$down_moon[0].":".$down_moon[1];exit;
            if($up_moon[0]<$down_moon[0])
            {
                $up_moon[0]         = $up_moon[0]+360;
                $diff               = explode(":",$this->subDegMinSec($up_moon[0],$up_moon[1],0,$down_moon[0],$down_moon[1],0));
            }
            else
            {
                $diff               = explode(":",$this->subDegMinSec($up_moon[0],$up_moon[1],0,$down_moon[0],$down_moon[1],0));
            }
            //echo $diff[0].":".$diff[1].":".$diff[2];exit;
            $day_transit        = explode(":",$this->divideDegMinSec($diff[0], $diff[1], $diff[2], $intval));
            if($intval2==0)
            {
                //echo $day_transit[0].":".$day_transit[1].":".$day_transit[2];exit;
                $total_transit      = explode(":",$down_moon[0].":".$down_moon[1].":00");
            }
            else
            {
                $total_transit      = explode(":",$this->addDegMinSec($down_moon[0], $down_moon[1], 0, $day_transit[0], $day_transit[1], $day_transit[2]));
            }
            
            //echo $total_transit[0].":".$total_transit[1].":".$total_transit[2];exit;
            $day_transit        = $day_transit[0]*60*4+$day_transit[1]*4;
            unset($sign);   // unset variable to reset it

            $time_diff          = substr($data['time_diff'],1);
            $sign               = substr($data['time_diff'],0,1);
            $time_diff          = explode(":",$time_diff);
            $time_diff          = $time_diff[0]*3600+$time_diff[1]*60+$time_diff[2];
            $intval             = 24*3600;
            $hr_transit         = explode(":",$this->getDiffTransit2($day_transit, $time_diff, $intval));
            //echo $hr_transit[0].":".$hr_transit[1].":".$hr_transit[2];exit;
            if($sign == "+")
            {
                $actual_transit    = explode(":",$this->addDegMinSec($total_transit[0], $total_transit[1], $total_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
            else if($sign == "-")
            {
                $actual_transit    = explode(":",$this->subDegMinSec($total_transit[0], $total_transit[1], $total_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
            //echo $actual_transit[0].":".$actual_transit[1].":".$actual_transit[2];exit;
            //$actual_transit         = round($actual_transit/(4*60),2);
            $date1  = null;
            unset($date1);
            $query                  ->clear();
            $query                  ->select($db->quoteName('ayanamsha'))
                                    ->from($db->quoteName('#__lahiri_ayanamsha'))
                                    ->where($db->quoteName('year').'<='.$db->quote($dob[0]))
                                    ->order($db->quoteName('year').' desc')
                                    ->setLimit('1');
            $db->setQuery($query);
            $result                 = $db->loadAssoc();
            $ayanamsha              = explode(":",$result['ayanamsha']);
            $moon                   = $this->subDegMinSec($actual_transit[0], $actual_transit[1], $actual_transit[2], $ayanamsha[0], $ayanamsha[1], $ayanamsha[2]);
            $moon                   = array("moon"=>$moon);
            
        //print_r($lagna);exit;*/
            
        }
        $data            = array_merge($data, $moon);
        //print_r($data);exit;
        $this->calculateSun($data);
    }
    protected function calculateSun($data)
    {
            //print_r($data);
        $dob        = explode("/",$data['dob']);
        $year       = (int)$dob[0];
        $month      = (int)$dob[1];
        $day        = (int)$dob[2];
        
        if($year <= 2000)
        {
            $db             = JFactory::getDbo();
            $query          = $db->getQuery(true);
            $query          ->select($db->quoteName(array('full_year','surya')));
            $query          ->from($db->quoteName('#__raman_planets2000'));
            $query          ->where($db->quoteName('year').'='.$db->quote($year).
                                    'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                                    $db->quoteName('day').'<='.$db->quote($day));
            $query          ->order($db->quoteName('day').' desc');
            $query          ->setLimit('1');
            $db             ->setQuery($query);
            $result         = $db->loadAssoc();
            $down_yob       = $result['full_year'];
            $down_deg       = explode(".",$result['surya']);

            $query              ->clear();
            $query              ->select($db->quoteName(array('full_year','surya')));
            $query              ->from($db->quoteName('#__raman_planets2000'));
            $query              ->where($db->quoteName('year').'='.$db->quote($year).
                                    'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                                    $db->quoteName('day').'>'.$db->quote($day));
            $query              ->order($db->quoteName('day').' asc');
            $query              ->setLimit('1');
            $db                 ->setQuery($query);
            $result             = $db->loadAssoc();
            $up_yob             = $result['full_year'];
            $up_deg             = explode(".",$result['surya']);
            $datetime1          = new DateTime($down_yob);          // lower value of surya from two arrived values
            $datetime2          = new DateTime($up_yob);       // exact dob
            $datetime3          = new DateTime($data['dob']);       // exact dob
            $interval           = $datetime1->diff($datetime2);     // get difference
            $intval             = (int)$interval->format('%a');     // format in int example 2
            $interval1           = $datetime1->diff($datetime3);
            $intval2            = (int)$interval1->format('%a');
            
            if($up_deg[0]<$down_deg[0])
            {
                $up_deg[0]         = $up_deg[0]+360;
                $diff               = explode(":",$this->subDegMinSec($up_deg[0],$up_deg[1],0,$down_deg[0],$down_deg[1],0));
            }
            else
            {
                $diff               = explode(":",$this->subDegMinSec($up_deg[0],$up_deg[1],0,$down_deg[0],$down_deg[1],0));
            }
            $day_transit        = explode(":",$this->divideDegMinSec($diff[0], $diff[1], $diff[2], $intval));       // one day transit
            if($intval2!==0)
            {
                $diff               = $diff[0]*60*4+$diff[1]*4;
                $dob_transit        = explode(":",$this->getDiffTransit2($diff,$intval2, $intval));
                $dob_transit        = explode(":",$this->addDegMinSec($down_deg[0], $down_deg[1], 0, $dob_transit[0], $dob_transit[1], $dob_transit[2]));
            }
            else
            {
                $dob_transit        = explode(":",$down_deg[0].":".$down_deg[1].":00");
            }
            //echo $dob_transit;exit;
            $time_diff          = substr($data['time_diff'],1);
            $sign               = substr($data['time_diff'],0,1);
            $time_diff          = explode(":",$time_diff);
            $time_diff          = $time_diff[0]*3600+$time_diff[1]*60+$time_diff[2];
            $intval             = 24*3600;
            $day_transit        = $day_transit[0]*60*4+$day_transit[1]*4;
            $hr_transit         = explode(":",$this->getDiffTransit2($day_transit, $time_diff, $intval));
            //echo $hr_transit;exit;
            if($sign == "+")
            {
                $actual_transit    = explode(":",$this->addDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
            else if($sign == "-")
            {
                $actual_transit    = explode(":",$this->subDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
             //$actual_transit         = round($actual_transit/(4*60),2);
            $date1  = null;
            unset($date1);
            $query                  ->clear();
            $query                  ->select($db->quoteName('ayanamsha'))
                                    ->from($db->quoteName('#__lahiri_ayanamsha'))
                                    ->where($db->quoteName('year').'<='.$db->quote($dob[0]))
                                    ->order($db->quoteName('year').' desc')
                                    ->setLimit('1');
            $db->setQuery($query);
            $result                 = $db->loadAssoc();
            $ayanamsha              = explode(":",$result['ayanamsha']);
            $surya                  = $this->subDegMinSec($actual_transit[0], $actual_transit[1], $actual_transit[2], $ayanamsha[0], $ayanamsha[1], $ayanamsha[2]);
            $surya                  = array("surya"=>$surya);
        //print_r($lagna);exit;*/
            
        }
        $data            = array_merge($data, $surya);
        //print_r($data);exit;
        $this->calculateMangal($data);   
    }
    protected function calculateMangal($data)
    {
               //print_r($data);
        $dob        = explode("/",$data['dob']);
        $year       = (int)$dob[0];
        $month      = (int)$dob[1];
        $day        = (int)$dob[2];
        
        if($year <= 2000)
        {
            $db             = JFactory::getDbo();
            $query          = $db->getQuery(true);
            $query          ->select($db->quoteName(array('full_year','mangal')));
            $query          ->from($db->quoteName('#__raman_planets2000'));
            $query          ->where($db->quoteName('year').'='.$db->quote($year).
                                    'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                                    $db->quoteName('day').'<='.$db->quote($day));
            $query          ->order($db->quoteName('day').' desc');
            $query          ->setLimit('1');
            $db             ->setQuery($query);
            $result         = $db->loadAssoc();
            $down_yob       = $result['full_year'];
            $down_deg       = explode(".",$result['mangal']);
     
            $query              ->clear();
            $query              ->select($db->quoteName(array('full_year','mangal')));
            $query              ->from($db->quoteName('#__raman_planets2000'));
            $query              ->where($db->quoteName('year').'='.$db->quote($year).
                                    'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                                    $db->quoteName('day').'>'.$db->quote($day));
            $query              ->order($db->quoteName('day').' asc');
            $query              ->setLimit('1');
            $db                 ->setQuery($query);
            $result             = $db->loadAssoc();
            $up_yob             = $result['full_year'];
            $up_deg             = explode(".",$result['mangal']);
            $datetime1          = new DateTime($down_yob);          // lower value of surya from two arrived values
            $datetime2          = new DateTime($up_yob);       // exact dob
            $datetime3          = new DateTime($data['dob']);       // exact dob
            $interval           = $datetime1->diff($datetime2);     // get difference
            $intval             = (int)$interval->format('%a');     // format in int example 2
            $interval1           = $datetime1->diff($datetime3);
            $intval2            = (int)$interval1->format('%a');
            $sub_diff           = $this->subDegMinSec($up_deg[0], $up_deg[1], 0, $down_deg[0], $down_deg[1], 0);
            if($up_deg[0]<$down_deg[0] && intval($up_deg[0]-$down_deg[0])>300)
            {
                $up_deg[0]         = $up_deg[0]+360;
                $diff               = explode(":",$this->subDegMinSec($up_deg[0],$up_deg[1],0,$down_deg[0],$down_deg[1],0));
            }
            else
            {
                $diff               = explode(":",$this->subDegMinSec($up_deg[0],$up_deg[1],0,$down_deg[0],$down_deg[1],0));
            }
            $day_transit        = explode(":",$this->divideDegMinSec($diff[0], $diff[1], $diff[2], $intval));       // one day transit
            if($intval2!==0)
            {
                $diff               = $diff[0]*60*4+$diff[1]*4;
                $dob_transit        = explode(":",$this->getDiffTransit2($diff,$intval2, $intval));
                $dob_transit        = explode(":",$this->addDegMinSec($down_deg[0], $down_deg[1], 0, $dob_transit[0], $dob_transit[1], $dob_transit[2]));
            }
            else
            {
                $dob_transit        = explode(":",$down_deg[0].":".$down_deg[1].":00");
            }
            //echo $dob_transit;exit;
            $time_diff          = substr($data['time_diff'],1);
            $sign               = substr($data['time_diff'],0,1);
            $time_diff          = explode(":",$time_diff);
            $time_diff          = $time_diff[0]*3600+$time_diff[1]*60+$time_diff[2];
            $intval             = 24*3600;
            $day_transit        = $day_transit[0]*60*4+$day_transit[1]*4;
            $hr_transit         = explode(":",$this->getDiffTransit2($day_transit, $time_diff, $intval));
            //echo $hr_transit;exit;
            if($sign == "+")
            {
                $actual_transit    = explode(":",$this->addDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
            else if($sign == "-")
            {
                $actual_transit    = explode(":",$this->subDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
             //$actual_transit         = round($actual_transit/(4*60),2);
            $date1  = null;
            unset($date1);
            $query                  ->clear();
            $query                  ->select($db->quoteName('ayanamsha'))
                                    ->from($db->quoteName('#__lahiri_ayanamsha'))
                                    ->where($db->quoteName('year').'<='.$db->quote($dob[0]))
                                    ->order($db->quoteName('year').' desc')
                                    ->setLimit('1');
            $db->setQuery($query);
            $result                 = $db->loadAssoc();
            $ayanamsha              = explode(":",$result['ayanamsha']);
            $mangal                 = $this->subDegMinSec($actual_transit[0], $actual_transit[1], $actual_transit[2], $ayanamsha[0], $ayanamsha[1], $ayanamsha[2]);
            if($down_deg[0]>$up_deg[0])
            {
                $mangal             = $mangal.":r";
            }
            $mangal                 = array("mangal"=>$mangal);
        //print_r($lagna);exit;*/
            
        }
        $data            = array_merge($data, $mangal);
        //print_r($data);exit;
        $this->calculateGuru($data);   
    }
    protected function calculateGuru($data)
    {
              //print_r($data);
        $dob        = explode("/",$data['dob']);
        $year       = (int)$dob[0];
        $month      = (int)$dob[1];
        $day        = (int)$dob[2];
        
        if($year <= 2000)
        {
            $db             = JFactory::getDbo();
            $query          = $db->getQuery(true);
            $query          ->select($db->quoteName(array('full_year','guru')));
            $query          ->from($db->quoteName('#__raman_planets2000'));
            $query          ->where($db->quoteName('year').'='.$db->quote($year).
                                    'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                                    $db->quoteName('day').'<='.$db->quote($day));
            $query          ->order($db->quoteName('day').' desc');
            $query          ->setLimit('1');
            $db             ->setQuery($query);
            $result         = $db->loadAssoc();
            $down_yob       = $result['full_year'];
            $down_deg       = explode(".",$result['guru']);
     
            $query              ->clear();
            $query              ->select($db->quoteName(array('full_year','guru')));
            $query              ->from($db->quoteName('#__raman_planets2000'));
            $query              ->where($db->quoteName('year').'='.$db->quote($year).
                                    'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                                    $db->quoteName('day').'>'.$db->quote($day));
            $query              ->order($db->quoteName('day').' asc');
            $query              ->setLimit('1');
            $db                 ->setQuery($query);
            $result             = $db->loadAssoc();
            $up_yob             = $result['full_year'];
            $up_deg             = explode(".",$result['guru']);
            $datetime1          = new DateTime($down_yob);          // lower value of surya from two arrived values
            $datetime2          = new DateTime($up_yob);       // exact dob
            $datetime3          = new DateTime($data['dob']);       // exact dob
            $interval           = $datetime1->diff($datetime2);     // get difference
            $intval             = (int)$interval->format('%a');     // format in int example 2
            $interval1           = $datetime1->diff($datetime3);
            $intval2            = (int)$interval1->format('%a');
            
            if($up_deg[0]<$down_deg[0] && intval($up_deg[0]-$down_deg[0])>300)
            {
                $up_deg[0]         = $up_deg[0]+360;
                $diff               = explode(":",$this->subDegMinSec($up_deg[0],$up_deg[1],0,$down_deg[0],$down_deg[1],0));
            }
            else
            {
                $diff               = explode(":",$this->subDegMinSec($up_deg[0],$up_deg[1],0,$down_deg[0],$down_deg[1],0));
            }
            $day_transit        = explode(":",$this->divideDegMinSec($diff[0], $diff[1], $diff[2], $intval));       // one day transit
            if($intval2!==0)
            {
                $diff               = $diff[0]*60*4+$diff[1]*4;
                $dob_transit        = explode(":",$this->getDiffTransit2($diff,$intval2, $intval));
                $dob_transit        = explode(":",$this->addDegMinSec($down_deg[0], $down_deg[1], 0, $dob_transit[0], $dob_transit[1], $dob_transit[2]));
            }
            else
            {
                $dob_transit        = explode(":",$down_deg[0].":".$down_deg[1].":00");
            }
            //echo $dob_transit;exit;
            $time_diff          = substr($data['time_diff'],1);
            $sign               = substr($data['time_diff'],0,1);
            $time_diff          = explode(":",$time_diff);
            $time_diff          = $time_diff[0]*3600+$time_diff[1]*60+$time_diff[2];
            $intval             = 24*3600;
            $day_transit        = $day_transit[0]*60*4+$day_transit[1]*4;
            $hr_transit         = explode(":",$this->getDiffTransit2($day_transit, $time_diff, $intval));
            //echo $hr_transit;exit;
            if($sign == "+")
            {
                $actual_transit    = explode(":",$this->addDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
            else if($sign == "-")
            {
                $actual_transit    = explode(":",$this->subDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
             //$actual_transit         = round($actual_transit/(4*60),2);
            $date1  = null;
            unset($date1);
            $query                  ->clear();
            $query                  ->select($db->quoteName('ayanamsha'))
                                    ->from($db->quoteName('#__lahiri_ayanamsha'))
                                    ->where($db->quoteName('year').'<='.$db->quote($dob[0]))
                                    ->order($db->quoteName('year').' desc')
                                    ->setLimit('1');
            $db->setQuery($query);
            $result                 = $db->loadAssoc();
            $ayanamsha              = explode(":",$result['ayanamsha']);
            $guru                   = $this->subDegMinSec($actual_transit[0], $actual_transit[1], $actual_transit[2], $ayanamsha[0], $ayanamsha[1], $ayanamsha[2]);
            
            if($down_deg[0]>$up_deg[0])
            {
                $guru               = $guru.":r";
            }
            $guru                   = array("guru"=>$guru);
                
        }
        $data            = array_merge($data, $guru);
        //print_r($data);exit;
        $this->calculateShukra($data);   
    }
    protected function calculateShukra($data)
    {
        $dob        = explode("/",$data['dob']);
        $year       = (int)$dob[0];
        $month      = (int)$dob[1];
        $day        = (int)$dob[2];
        
        if($year <= 2000)
        {
            $db             = JFactory::getDbo();
            $query          = $db->getQuery(true);
            $query          ->select($db->quoteName(array('full_year','shukra')));
            $query          ->from($db->quoteName('#__raman_planets2000'));
            $query          ->where($db->quoteName('year').'='.$db->quote($year).
                                    'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                                    $db->quoteName('day').'<='.$db->quote($day));
            $query          ->order($db->quoteName('day').' desc');
            $query          ->setLimit('1');
            $db             ->setQuery($query);
            $result         = $db->loadAssoc();
            $down_yob       = $result['full_year'];
            $down_deg       = explode(".",$result['shukra']);
     
            $query              ->clear();
            $query              ->select($db->quoteName(array('full_year','shukra')));
            $query              ->from($db->quoteName('#__raman_planets2000'));
            $query              ->where($db->quoteName('year').'='.$db->quote($year).
                                    'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                                    $db->quoteName('day').'>'.$db->quote($day));
            $query              ->order($db->quoteName('day').' asc');
            $query              ->setLimit('1');
            $db                 ->setQuery($query);
            $result             = $db->loadAssoc();
            $up_yob             = $result['full_year'];
            $up_deg             = explode(".",$result['shukra']);
            $datetime1          = new DateTime($down_yob);          // lower value of surya from two arrived values
            $datetime2          = new DateTime($up_yob);       // exact dob
            $datetime3          = new DateTime($data['dob']);       // exact dob
            $interval           = $datetime1->diff($datetime2);     // get difference
            $intval             = (int)$interval->format('%a');     // format in int example 2
            $interval1           = $datetime1->diff($datetime3);
            $intval2            = (int)$interval1->format('%a');
            
            if($up_deg[0]<$down_deg[0] && intval($up_deg[0]-$down_deg[0])>300)
            {
                $up_deg[0]         = $up_deg[0]+360;
                $diff               = explode(":",$this->subDegMinSec($up_deg[0],$up_deg[1],0,$down_deg[0],$down_deg[1],0));
            }
            else
            {
                $diff               = explode(":",$this->subDegMinSec($up_deg[0],$up_deg[1],0,$down_deg[0],$down_deg[1],0));
            }
            $day_transit        = explode(":",$this->divideDegMinSec($diff[0], $diff[1], $diff[2], $intval));       // one day transit
            if($intval2!==0)
            {
                $diff               = $diff[0]*60*4+$diff[1]*4;
                $dob_transit        = explode(":",$this->getDiffTransit2($diff,$intval2, $intval));
                $dob_transit        = explode(":",$this->addDegMinSec($down_deg[0], $down_deg[1], 0, $dob_transit[0], $dob_transit[1], $dob_transit[2]));
            }
            else
            {
                $dob_transit        = explode(":",$down_deg[0].":".$down_deg[1].":00");
            }
            //echo $dob_transit;exit;
            $time_diff          = substr($data['time_diff'],1);
            $sign               = substr($data['time_diff'],0,1);
            $time_diff          = explode(":",$time_diff);
            $time_diff          = $time_diff[0]*3600+$time_diff[1]*60+$time_diff[2];
            $intval             = 24*3600;
            $day_transit        = $day_transit[0]*60*4+$day_transit[1]*4;
            $hr_transit         = explode(":",$this->getDiffTransit2($day_transit, $time_diff, $intval));
            //echo $hr_transit;exit;
            if($sign == "+")
            {
                $actual_transit    = explode(":",$this->addDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
            else if($sign == "-")
            {
                $actual_transit    = explode(":",$this->subDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
             //$actual_transit         = round($actual_transit/(4*60),2);
            $date1  = null;
            unset($date1);
            $query                  ->clear();
            $query                  ->select($db->quoteName('ayanamsha'))
                                    ->from($db->quoteName('#__lahiri_ayanamsha'))
                                    ->where($db->quoteName('year').'<='.$db->quote($dob[0]))
                                    ->order($db->quoteName('year').' desc')
                                    ->setLimit('1');
            $db->setQuery($query);
            $result                 = $db->loadAssoc();
            $ayanamsha              = explode(":",$result['ayanamsha']);
            $shukra                 = $this->subDegMinSec($actual_transit[0], $actual_transit[1], $actual_transit[2], $ayanamsha[0], $ayanamsha[1], $ayanamsha[2]);
            
            if($down_deg[0]>$up_deg[0])
            {
                $shukra               = $shukra.":r";
            }
            $shukra                   = array("shukra"=>$shukra);      
        }
        $data            = array_merge($data, $shukra);
        //print_r($data);exit;
        $this->calculateShani($data);
    }
    protected function calculateShani($data)
    {
        $dob        = explode("/",$data['dob']);
        $year       = (int)$dob[0];
        $month      = (int)$dob[1];
        $day        = (int)$dob[2];
        
        if($year <= 2000)
        {
            $db             = JFactory::getDbo();
            $query          = $db->getQuery(true);
            $query          ->select($db->quoteName(array('full_year','shani')));
            $query          ->from($db->quoteName('#__raman_planets2000'));
            $query          ->where($db->quoteName('year').'='.$db->quote($year).
                                    'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                                    $db->quoteName('day').'<='.$db->quote($day));
            $query          ->order($db->quoteName('day').' desc');
            $query          ->setLimit('1');
            $db             ->setQuery($query);
            $result         = $db->loadAssoc();
            $down_yob       = $result['full_year'];
            $down_deg       = explode(".",$result['shani']);
     
            $query              ->clear();
            $query              ->select($db->quoteName(array('full_year','shani')));
            $query              ->from($db->quoteName('#__raman_planets2000'));
            $query              ->where($db->quoteName('year').'='.$db->quote($year).
                                    'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                                    $db->quoteName('day').'>'.$db->quote($day));
            $query              ->order($db->quoteName('day').' asc');
            $query              ->setLimit('1');
            $db                 ->setQuery($query);
            $result             = $db->loadAssoc();
            $up_yob             = $result['full_year'];
            $up_deg             = explode(".",$result['shani']);
            $datetime1          = new DateTime($down_yob);          // lower value of surya from two arrived values
            $datetime2          = new DateTime($up_yob);       // exact dob
            $datetime3          = new DateTime($data['dob']);       // exact dob
            $interval           = $datetime1->diff($datetime2);     // get difference
            $intval             = (int)$interval->format('%a');     // format in int example 2
            $interval1           = $datetime1->diff($datetime3);
            $intval2            = (int)$interval1->format('%a');
            
            if($up_deg[0]<$down_deg[0] && intval($up_deg[0]-$down_deg[0])>300)
            {
                $up_deg[0]         = $up_deg[0]+360;
                $diff               = explode(":",$this->subDegMinSec($up_deg[0],$up_deg[1],0,$down_deg[0],$down_deg[1],0));
            }
            else
            {
                $diff               = explode(":",$this->subDegMinSec($up_deg[0],$up_deg[1],0,$down_deg[0],$down_deg[1],0));
            }
            $day_transit        = explode(":",$this->divideDegMinSec($diff[0], $diff[1], $diff[2], $intval));       // one day transit
            if($intval2!==0)
            {
                $diff               = $diff[0]*60*4+$diff[1]*4;
                $dob_transit        = explode(":",$this->getDiffTransit2($diff,$intval2, $intval));
                $dob_transit        = explode(":",$this->addDegMinSec($down_deg[0], $down_deg[1], 0, $dob_transit[0], $dob_transit[1], $dob_transit[2]));
            }
            else
            {
                $dob_transit        = explode(":",$down_deg[0].":".$down_deg[1].":00");
            }
            //echo $dob_transit;exit;
            $time_diff          = substr($data['time_diff'],1);
            $sign               = substr($data['time_diff'],0,1);
            $time_diff          = explode(":",$time_diff);
            $time_diff          = $time_diff[0]*3600+$time_diff[1]*60+$time_diff[2];
            $intval             = 24*3600;
            $day_transit        = $day_transit[0]*60*4+$day_transit[1]*4;
            $hr_transit         = explode(":",$this->getDiffTransit2($day_transit, $time_diff, $intval));
            //echo $hr_transit;exit;
            if($sign == "+")
            {
                $actual_transit    = explode(":",$this->addDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
            else if($sign == "-")
            {
                $actual_transit    = explode(":",$this->subDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
             //$actual_transit         = round($actual_transit/(4*60),2);
            $date1  = null;
            unset($date1);
            $query                  ->clear();
            $query                  ->select($db->quoteName('ayanamsha'))
                                    ->from($db->quoteName('#__lahiri_ayanamsha'))
                                    ->where($db->quoteName('year').'<='.$db->quote($dob[0]))
                                    ->order($db->quoteName('year').' desc')
                                    ->setLimit('1');
            $db->setQuery($query);
            $result                 = $db->loadAssoc();
            $ayanamsha              = explode(":",$result['ayanamsha']);
            $shani                  = $this->subDegMinSec($actual_transit[0], $actual_transit[1], $actual_transit[2], $ayanamsha[0], $ayanamsha[1], $ayanamsha[2]);
            
            if($down_deg[0]>$up_deg[0])
            {
                $shani              = $shani.":r";
            }
            $shani                  = array("shani"=>$shani);      
        }
        $data            = array_merge($data, $shani);
        //print_r($data);exit;
        $this->calculateRahu($data);
    }
    protected function calculateRahu($data)
    {
        $dob        = explode("/",$data['dob']);
        $year       = (int)$dob[0];
        $month      = (int)$dob[1];
        $day        = (int)$dob[2];
        
        if($year <= 2000)
        {
            $db             = JFactory::getDbo();
            $query          = $db->getQuery(true);
            $query          ->select($db->quoteName(array('full_year','rahu')));
            $query          ->from($db->quoteName('#__raman_planets2000'));
            $query          ->where($db->quoteName('year').'='.$db->quote($year).
                                    'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                                    $db->quoteName('day').'<='.$db->quote($day));
            $query          ->order($db->quoteName('day').' desc');
            $query          ->setLimit('1');
            $db             ->setQuery($query);
            $result         = $db->loadAssoc();
            $down_yob       = $result['full_year'];
            $down_deg       = explode(".",$result['rahu']);
     
            $query              ->clear();
            $query              ->select($db->quoteName(array('full_year','rahu')));
            $query              ->from($db->quoteName('#__raman_planets2000'));
            $query              ->where($db->quoteName('year').'='.$db->quote($year).
                                    'AND'.$db->quoteName('month').'='.$db->quote($month).'AND'.
                                    $db->quoteName('day').'>'.$db->quote($day));
            $query              ->order($db->quoteName('day').' asc');
            $query              ->setLimit('1');
            $db                 ->setQuery($query);
            $result             = $db->loadAssoc();
            $up_yob             = $result['full_year'];
            $up_deg             = explode(".",$result['rahu']);
            $datetime1          = new DateTime($down_yob);          // lower value of surya from two arrived values
            $datetime2          = new DateTime($up_yob);       // exact dob
            $datetime3          = new DateTime($data['dob']);       // exact dob
            $interval           = $datetime1->diff($datetime2);     // get difference
            $intval             = (int)$interval->format('%a');     // format in int example 2
            $interval1           = $datetime1->diff($datetime3);
            $intval2            = (int)$interval1->format('%a');
            
            if(intval($up_deg[0]-$down_deg[0])>300)
            {
                $up_deg[0]         = $up_deg[0]+360;
                $diff               = explode(":",$this->subDegMinSec($up_deg[0],$up_deg[1],0,$down_deg[0],$down_deg[1],0));
            }
            else
            {
                $diff               = explode(":",$this->subDegMinSec($up_deg[0],$up_deg[1],0,$down_deg[0],$down_deg[1],0));
            }
            $day_transit        = explode(":",$this->divideDegMinSec($diff[0], $diff[1], $diff[2], $intval));       // one day transit
            if($intval2!==0)
            {
                $diff               = $diff[0]*60*4+$diff[1]*4;
                $dob_transit        = explode(":",$this->getDiffTransit2($diff,$intval2, $intval));
                $dob_transit        = explode(":",$this->addDegMinSec($down_deg[0], $down_deg[1], 0, $dob_transit[0], $dob_transit[1], $dob_transit[2]));
            }
            else
            {
                $dob_transit        = explode(":",$down_deg[0].":".$down_deg[1].":00");
            }
            //echo $dob_transit;exit;
            $time_diff          = substr($data['time_diff'],1);
            $sign               = substr($data['time_diff'],0,1);
            $time_diff          = explode(":",$time_diff);
            $time_diff          = $time_diff[0]*3600+$time_diff[1]*60+$time_diff[2];
            $intval             = 24*3600;
            $day_transit        = $day_transit[0]*60*4+$day_transit[1]*4;
            $hr_transit         = explode(":",$this->getDiffTransit2($day_transit, $time_diff, $intval));
            //echo $hr_transit;exit;
            if($sign == "+")
            {
                $actual_transit    = explode(":",$this->addDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
            else if($sign == "-")
            {
                $actual_transit    = explode(":",$this->subDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
             //$actual_transit         = round($actual_transit/(4*60),2);
            $date1  = null;
            unset($date1);
            $query                  ->clear();
            $query                  ->select($db->quoteName('ayanamsha'))
                                    ->from($db->quoteName('#__lahiri_ayanamsha'))
                                    ->where($db->quoteName('year').'<='.$db->quote($dob[0]))
                                    ->order($db->quoteName('year').' desc')
                                    ->setLimit('1');
            $db->setQuery($query);
            $result                 = $db->loadAssoc();
            $ayanamsha              = explode(":",$result['ayanamsha']);
            $rahu                   = $this->subDegMinSec($actual_transit[0], $actual_transit[1], $actual_transit[2], $ayanamsha[0], $ayanamsha[1], $ayanamsha[2]);
            $val                    = explode(":",$rahu);
            $val[0]                 = $val[0]+180;
            if($val[0]>=360)
            {
                $val[0]               = $val[0]-360;
            }
            $ketu                   = $val[0].":".$val[1].":".$val[2];
            $values                 = array("rahu"=>$rahu,"ketu"=>$ketu);
        }
        $data            = array_merge($data, $values);
        print_r($data);exit;
    }
}
?>
