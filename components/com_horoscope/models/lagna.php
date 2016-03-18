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
        $fname          = $user_details['fname'];
        $gender         = $user_details['gender'];
        $dob            = $user_details['dob'];
        $year           = date("Y",strtotime($dob));
        $tob            = $user_details['tob'];
        $pob            = $user_details['pob'];
        $lon            = $user_details['lon'];
        $lat            = $user_details['lat'];
        $tmz            = $user_details['tmz'];
        
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
        //echo $tob;exit;
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
     
        $data  = array(
                        "fname"=>$fname,"gender"=>$gender,"dob"=>$dob,
                        "tob"=>$tob,"pob"=>$pob,"lon"=>$lon,"lat"=>$lat,"tmz"=>$tmz,
                        "tmz_hr"=>$gmt,"time_diff"=>$diff
                    );
        if($year <= 2000)
        {
            $this->data     = $this->getBudh($data);
        }
        else
        {
            $this->data     = $this->getRaman2050($data);
        }
        return $this->data;
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
        $deg        = round(($decimal/(60*4)),4);
        $real_deg   = explode(".",$deg);
        $min        = abs(($deg-$real_deg[0])*60);
        $real_min   = explode(".", $min);
        $sec        = abs(($deg-$real_deg[0]-($real_min[0]/60))*3600);
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
        while($sec2>$sec1)
        {
            $min1       = $min1-1;
            $sec1       = $sec1+60;
        }
        while($min2>$min1)
        {
            $deg1       = $deg1-1;
            $min1       = $min1+60;
        }
        if($deg2 > $deg1)
        $deg            = $deg2 - $deg1;
        else
        $deg            = $deg1 - $deg2;
        $min            = $min1-$min2;
        $sec            = $sec1-$sec2;
        $value          = $deg.":".$min.":".$sec;
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
    // Get planet sign
    public function calcDetails($planet)
    {
        $details        = explode(":", $planet);
        $sign_num       = intval($details[0]/30);
        
        switch($sign_num)
        {
            case 0:
            return "Aries";break;
            case 1:
            return "Taurus";break;
            case 2:
            return "Gemini";break;
            case 3:
            return "Cancer";break;
            case 4:
            return "Leo";break;
            case 5:
            return "Virgo";break;
            case 6:
            return "Libra";break;
            case 7:
            return "Scorpio";break;
            case 8:
            return "Sagittarius";break;
            case 9:
            return "Capricorn";break;
            case 10:
            return "Aquarius";break;
            case 11:
            return "Pisces";break;
        }
    }
    // Calculate Distance travelled in sign
    public function calcDistance($planet)
    {
        $details        = explode(":", $planet);
        $sign_num       = intval($details[0]%30);
        return $sign_num."&deg;".$details[1]."'";
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
        //echo $dateObject;exit;
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
        
        return $lagna;  
    }
    protected function getMoonData($data)
    {
        //print_r($data);exit;
        $dob        = explode("/",$data['dob']);
        $year       = (int)$dob[0];
        $month      = (int)$dob[1];
        $day        = (int)$dob[2];
 
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
        //echo $down_yob.":".$up_yob;exit;
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
            //echo "calls";exit;
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
        if($actual_transit[0]<$ayanamsha[0])
        {
            $actual_transit[0]  = $actual_transit[0]+360;
        }
        $moon                   = $this->subDegMinSec($actual_transit[0], $actual_transit[1], $actual_transit[2], $ayanamsha[0], $ayanamsha[1], $ayanamsha[2]);
        return $moon;
    }
    protected function calculate7Planets($data)
    {
            //print_r($data);
        $dob        = date("Y-m-d", strtotime($data['dob']));
        $year       = date("Y", strtotime($data['dob']));
        $seven_planets     = array();
        $planets    = array("full_year","surya","mangal","guru","shukra","shani","rahu");
        $count          = count($planets);
        // getting lower value
        $db             = JFactory::getDbo();
        $query          = $db->getQuery(true);
        $query          ->select($db->quoteName($planets));
        $query          ->from($db->quoteName('#__raman_planets2000'));
        $query          ->where($db->quoteName('full_year').'<='.$db->quote($dob));
        $query          ->order($db->quoteName('full_year').' desc');
        $query          ->setLimit('1');
        $db             ->setQuery($query);
        $result1        = $db->loadAssoc();
        $down_year      = $result1['full_year'];
        //print_r($result1);
        $query          ->clear();
        $query          ->select($db->quoteName($planets));
        $query          ->from($db->quoteName('#__raman_planets2000'));
        $query          ->where($db->quoteName('full_year').'>'.$db->quote($dob));
        $query          ->order($db->quoteName('full_year').' asc');
        $query          ->setLimit('1');
        $db             ->setQuery($query);
        $result2        = $db->loadAssoc();
        $up_year        = $result2['full_year'];
        //print_r($result2);exit;
        //echo $up_deg.":".$down_deg;exit;
        $datetime1          = new DateTime($down_year);          // lower value of year
        $datetime2          = new DateTime($up_year);           // upper value of year
        $datetime3          = new DateTime($data['dob']);       // exact dob
        $interval           = $datetime1->diff($datetime2);     // get difference
        $intval             = (int)$interval->format('%a');     // format in int example 2
        $interval1          = $datetime1->diff($datetime3);
        $intval2            = (int)$interval1->format('%a'); 
        for($i=1;$i<$count;$i++)
        {

            $planet         = $planets[$i];         // planet eg. sun, moon etc
            $down_deg       = $result1[$planet];        // lower value of planet
            $up_deg         = $result2[$planet];        // upper value of planet
            $down_val       = explode(".",$down_deg);
            $up_val         = explode(".",$up_deg);
            // checks if difference between lower and upper value is greater then 300.
            // In other words if lower value is in pisces sign(360) and upper value is aries sign(0)
            if($up_deg<$down_deg && intval($up_deg-$down_deg)>300)      
            {
                $up_val[0]      = $up_val[0]+360;      // adds 360 degree to upper value if it is aries sign and lower value in pisces sign 
                $diff           = explode(":",$this->subDegMinSec($up_val[0],$up_val[1],0,$down_val[0],$down_val[1],0));
            }
            else
            {
                $diff           = explode(":",$this->subDegMinSec($up_val[0],$up_val[1],0,$down_val[0],$down_val[1],0));
            }
            //echo $diff[0].":".$diff[1].":".$diff[2];exit;
            $day_transit        = explode(":",$this->divideDegMinSec($diff[0], $diff[1], $diff[2], $intval));       // one day transit
            $diff               = $diff[0]*60*4+$diff[1]*4;
            //echo $day_transit[0].":".$day_transit[2].":".$day_transit[2];exit;
            if($intval2!==0)
            {
                $dob_transit        = explode(":",$this->getDiffTransit2($diff,$intval2, $intval));
                $dob_transit        = explode(":",$this->addDegMinSec($down_val[0], $down_val[1], 0, $dob_transit[0], $dob_transit[1], $dob_transit[2]));
            }
            else
            {
                $dob_transit        = explode(":",$down_val[0].":".$down_val[1].":00");
            }
            //echo $dob_transit[0].":".$dob_transit[1].":".$dob_transit[2];exit;
            $time_diff          = substr($data['time_diff'],1);
            $sign               = substr($data['time_diff'],0,1);
            $time_diff          = explode(":",$time_diff);
            $time_diff          = $time_diff[0]*3600+$time_diff[1]*60+$time_diff[2];
            $intval_sec         = 24*3600;
            $day_transit        = $day_transit[0]*60*4+$day_transit[1]*4;
            $hr_transit         = explode(":",$this->getDiffTransit2($day_transit, $time_diff, $intval_sec));
            //echo $hr_transit[0].":".$hr_transit[1].":".$hr_transit[2];exit;
            if($sign == "+")
            {
                $actual_transit    = explode(":",$this->addDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
            else if($sign == "-")
            {
                $actual_transit    = explode(":",$this->subDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
            }
            //echo $actual_transit[0].":".$actual_transit[1].":".$actual_transit[2];exit;
            $date1  = null;
            unset($date1);
            $query                  ->clear();
            $query                  ->select($db->quoteName('ayanamsha'))
                                    ->from($db->quoteName('#__lahiri_ayanamsha'))
                                    ->where($db->quoteName('year').'<='.$db->quote($year))
                                    ->order($db->quoteName('year').' desc')
                                    ->setLimit('1');
            $db->setQuery($query);
            $result                 = $db->loadAssoc();
            $ayanamsha              = explode(":",$result['ayanamsha'].":00");
            if($actual_transit[0]<$ayanamsha[0])
            {
                $actual_transit[0]  = $actual_transit[0]+360;
            }
            $value                  = $this->subDegMinSec($actual_transit[0], $actual_transit[1], $actual_transit[2], $ayanamsha[0], $ayanamsha[1], $ayanamsha[2]);
            unset($result);
            $value_details          = $this->calcDetails($value);
            $value_distance     = $this->calcDistance($value);
            if($up_deg<$down_deg && !(intval($up_deg-$down_deg)>300))
            {
                $result                 = array($planet=>$value.":r",$planet."_details"=>$value_details,
                                                $planet."_distance"=>$value_distance);
            }
            else
            {
                $result                 = array($planet=>$value,$planet."_details"=>$value_details,
                                                $planet."_distance"=>$value_distance);
            }
            
            $seven_planets                     = array_merge($seven_planets, $result);
          }
        return $seven_planets   ;
    }
    // function calculates value of Budh and also Ketu
    protected function getBudh($data)
    {
        $lagna              = $this->calculatelagna($data);
        $lagna_details      = $this->calcDetails($lagna);
        $lagna_distance     = $this->calcDistance($lagna);

        $lagna              = array("lagna"=>$lagna,"lagna_sign"=>$lagna_details,
                                    "lagna_distance"=>$lagna_distance);
        $moon               = $this->getMoonData($data);
        $moon_details       = $this->calcDetails($moon);
        $moon_distance      = $this->calcDistance($moon);
        $moon               = array("moon"=>$moon,"moon_details"=>$moon_details,
                                    "moon_distance"=>$moon_distance);
        
        $planets    = $this->calculate7Planets($data);
        
        $dob        = date("Y-m-d", strtotime($data['dob']));
        $year       = date("Y", strtotime($data['dob']));
        $rahu       = explode(":",$planets['rahu']);
        $ketu       = $rahu[0]+180;
        if($ketu >= 360)
        {
            $ketu   = $ketu-360;
        }
        $ketu           = $ketu.":".$rahu[1].":".$rahu[2];
        $ketu_distance  = $this->calcDistance($ketu);
        $ketu_details   = $this->calcDetails($ketu);
        $ketu           = array("ketu"=>$ketu.":r","ketu_details"=>$ketu_details,
                                    "ketu_distance"=>$ketu_distance);
        $db             = JFactory::getDbo();
        $query          = $db->getQuery(true);
        $query          ->select($db->quoteName(array("budh", "budh_5","full_year")));
        $query          ->from($db->quoteName('#__raman_planets2000'));
        $query          ->where($db->quoteName('full_year').'<='.$db->quote($dob));
        $query          ->order($db->quoteName('full_year').' desc');
        $query          ->setLimit('1');
        $db             ->setQuery($query);
        $result         = $db->loadAssoc();
        $down_year      = $result['full_year'];
        $down_budh      = $result['budh'];
        $down_budh5     = $result['budh_5'];
        unset($result);
        $query          ->clear();
        $query          ->select($db->quoteName(array("budh", "budh_5","full_year")));
        $query          ->from($db->quoteName('#__raman_planets2000'));
        $query          ->where($db->quoteName('full_year').'>'.$db->quote($dob));
        $query          ->order($db->quoteName('full_year').' asc');
        $query          ->setLimit('1');
        $db             ->setQuery($query);
        $result         = $db->loadAssoc();
        $up_year      = $result['full_year'];
        $up_budh      = $result['budh'];
        $up_budh5     = $result['budh_5'];
        //echo $down_year.":".$up_year;exit;
        $datetime1          = new DateTime($data['dob']);          // lower value of year
        $datetime2          = new DateTime($down_year);           // upper value of year
        $datetime3          = new DateTime($up_year);       // exact dob
        $interval1          = $datetime1->diff($datetime2);     // get difference
        $intval1            = (int)$interval1->format('%a');     // format in int example 2
        $interval2          = $datetime1->diff($datetime3);
        $intval2            = (int)$interval2->format('%a');
        //echo $intval1.":".$intval2;exit;
        if($intval1 >$intval2 && $down_budh5 !== "0")
        {
            $down_val           = $down_budh5;
            $up_val             = $up_budh;
            $intval             = 5;
        }
        else if($intval1 >$intval2 && $down_budh5 !== "0")
        {
            $down_val           = $down_budh;
            $up_val             = $down_budh5;
            $intval             = 5;
        }
        else
        {
            $down_val           = $down_budh;
            $up_val             = $up_budh;
            $intval             = 10;
        }
        if($up_val<$down_val && intval($up_val-$down_val)>300)
        {
            $up_val         = $up_val+360.00;
            $up_val         = explode(".",$up_val);
            $down_val       = explode(".",$down_val);
            $diff           = explode(":",$this->subDegMinSec($up_val[0],$up_val[1],0,$down_val[0],$down_val[1],0));
        }
        else
        {
            $up_val         = explode(".",$up_val);
            $down_val       = explode(".",$down_val);
            $diff           = explode(":",$this->subDegMinSec($up_val[0],$up_val[1],0,$down_val[0],$down_val[1],0));
        }
        $day_transit        = explode(":",$this->divideDegMinSec($diff[0], $diff[1], $diff[2], $intval));       // one day transit
        $diff               = $diff[0]*60*4+$diff[1]*4;
        //echo $day_transit[0].":".$day_transit[2].":".$day_transit[2];exit;
        if($intval1==0)
        {
            $dob_transit        = explode(":",$down_val[0].":".$down_val[1].":00");
        }
        else
        {
            $dob_transit        = explode(":",$this->getDiffTransit2($diff,$intval1, $intval));
            $dob_transit        = explode(":",$this->addDegMinSec($down_val[0], $down_val[1], 0, $dob_transit[0], $dob_transit[1], $dob_transit[2]));
        }
        //echo $dob_transit[0].":".$dob_transit[1].":".$dob_transit[2];exit;
        $time_diff          = substr($data['time_diff'],1);
        $sign               = substr($data['time_diff'],0,1);
        $time_diff          = explode(":",$time_diff);
        $time_diff          = $time_diff[0]*3600+$time_diff[1]*60+$time_diff[2];
        $intval_sec         = 24*3600;
        $day_transit        = $day_transit[0]*60*4+$day_transit[1]*4;
        $hr_transit         = explode(":",$this->getDiffTransit2($day_transit, $time_diff, $intval_sec));
        //echo $hr_transit[0].":".$hr_transit[1].":".$hr_transit[2];exit;
        if($sign == "+")
        {
            $actual_transit    = explode(":",$this->addDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
        }
        else if($sign == "-")
        {
            $actual_transit    = explode(":",$this->subDegMinSec($dob_transit[0], $dob_transit[1], $dob_transit[2], $hr_transit[0], $hr_transit[1], $hr_transit[2]));
        }
        //echo $actual_transit[0].":".$actual_transit[1].":".$actual_transit[2];exit;
        $date1  = null;
        unset($date1);
        $query                  ->clear();
        $query                  ->select($db->quoteName('ayanamsha'))
                                ->from($db->quoteName('#__lahiri_ayanamsha'))
                                ->where($db->quoteName('year').'<='.$db->quote($year))
                                ->order($db->quoteName('year').' desc')
                                ->setLimit('1');
        $db->setQuery($query);
        $result                 = $db->loadAssoc();
        $ayanamsha              = explode(":",$result['ayanamsha'].":00");
        //echo "<br/>".$ayanamsha[0].":".$ayanamsha[1].":".$ayanamsha[2];exit;
        if($actual_transit[0]<$ayanamsha[0])
        {
            $actual_transit[0]  = $actual_transit[0]+360;
        }
        $value                  = $this->subDegMinSec($actual_transit[0], $actual_transit[1], $actual_transit[2], $ayanamsha[0], $ayanamsha[1], $ayanamsha[2]);
        unset($result);
        if($up_deg<$down_deg && !(intval($up_deg-$down_deg)>300))
        {
            $budh                   = $value.":r";
        }
        else
        {
            $budh                   = $value;
        }
        $budh_distance              = $this->calcDistance($budh);
        $budh_details               = $this->calcDetails($budh);
        $budh                   = array("budh"=>$budh,"budh_distance"=>$budh_distance,
                                        "budh_details"=>$budh_details);
        $data                   = array_merge($data,$lagna,$moon,$planets,$ketu,$budh);
        return $data;
    }
   
    protected function getRaman2050($data)
    {
        $lagna          = $this->calculatelagna($data);
        $dob            = $data['dob'];
        $tob            = strtotime($data['tob']);
        $tob            = explode(":",date('G:i:s', $tob));

        $planets        = array("full_year","moon","sun","mars","mercury","jupiter","venus","saturn","rahu");
        $count          = count($planets);
        $db             = JFactory::getDbo();
        $query          = $db->getQuery(true);
        $query          ->select($db->quoteName($planets));
        $query          ->from($db->quoteName('#__raman_2050planets'));
        $query          ->where($db->quoteName('full_year').'='.$db->quote($dob));
        $query          ->order($db->quoteName('full_year').' desc');
        $query          ->setLimit('1');
        $db             ->setQuery($query);
        $result1        = $db->loadAssoc();
        
        $query          ->clear();
        $query          ->select($db->quoteName($planets));
        $query          ->from($db->quoteName('#__raman_2050planets'));
        $query          ->where($db->quoteName('full_year').'>'.$db->quote($dob));
        $query          ->order($db->quoteName('full_year').' asc');
        $query          ->setLimit('1');
        $db             ->setQuery($query);
        $result2        = $db->loadAssoc();
        for($i=1;$i<$count;$i++)
        {
            $planet         = $planets[$i];
            $down_deg       = explode(".",$result1[$planet]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
            $up_deg         = explode(".",$result2[$planet]);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
            (double)$down_deg       = ((double)$down_deg[0]*30+$down_deg[1]).".".$down_deg[2];
            (double)$up_deg         = ((double)$up_deg[0]*30+$up_deg[1]).".".$up_deg[2];
            $down_val               = explode(".",$down_deg);
            $up_val                 = explode(".", $up_deg);
            //echo $planet."  ".$up_deg." : ".$down_deg."<br/>";
            if($up_deg < $down_deg)
            {
                $diff               = explode(":", $this->subDegMinSec($down_val[0], $down_val[1], 0, $up_val[0], $up_val[1], 0));
            }
            else
            {
                $diff               = explode(":", $this->subDegMinSec($up_val[0], $up_val[1], 0, $down_val[0], $down_val[1], 0));
            }
            $deg                = $diff[0];
            $min                = $diff[1];
            if($min < 10)
            {
                $min            = "0".$min;
            }
            $query              ->clear();
            $query          ->select($db->quoteName(array("value")));
            $query          ->from($db->quoteName('#__raman_log'));
            $query          ->where($db->quoteName('degree').'='.$db->quote($deg).'AND'.
                                    $db->quoteName('min')."=".$db->quote($min));
            $db             ->setQuery($query);
            $result3         = $db->loadAssoc();
            
            $tob_deg        = $tob[0];
            $tob_min        = $tob[1];
            $query          ->clear();
            $query          ->select($db->quoteName(array("value")));
            $query          ->from($db->quoteName('#__raman_log'));
            $query          ->where($db->quoteName('degree').'='.$db->quote($tob_deg).'AND'.
                                    $db->quoteName('min')."=".$db->quote($tob_min));
            $db             ->setQuery($query);
            $result4        = $db->loadAssoc();
            
            $value1         = $result3["value"];
            $value2         = $result4["value"];
            
            $result         = number_format(($value1 + $value2),4);
            $query          ->clear();
            $query          ->select($db->quoteName(array('degree','min')));
            $query          ->from($db->quoteName('#__raman_log'));
            $query          ->where($db->quoteName('value').'<='.$db->quote($result));
            $query          ->order($db->quoteName('value').' desc');
            $query          ->setLimit('1');
            $db             ->setQuery($query);
            $result5        = $db->loadAssoc();
            $diff           = $result5["degree"].".".$result5["min"];
              
            //echo $down_deg." : ".$diff."<br/>";
            if($up_deg < $down_deg)
            {
				if($result5['min'] < 10)
                {
                    $diff   = $result5['degree'].'.0'.$result5['min'];
                }
                $distance       = ($down_deg - $diff)-30.00;
                $graha          = array($planet=>$distance.".r");
            }
            else
            {
                $distance       = ($down_deg + $diff)-30.00;
                $graha          = array($planet=>$distance);
            }
            if($i==8)
            {
                $ketu           = $distance+180;
                if($ketu>360)
                {
                    $ketu       = $ketu-360;
                }
                $ketu           = array("ketu"=> $ketu.".r");
                $data           = array_merge($data,$graha, $ketu);
            }  
            
            $data           = array_merge($data, $lagna, $graha);
                       
        }   
        return $data;
    }
}
?>
