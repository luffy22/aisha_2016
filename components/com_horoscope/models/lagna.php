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
    public $tz;
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
        $this->tz           = $user_details['tz'];
        
        echo $this->getSiderealTime();
      
    }
    // Method to get the sidereal Time
    public function getSiderealTime()
    {
        $lon            = explode(":", $this->lon);
        $dob            = explode("/",$this->dob);
        $monthNum       = $dob[0];  // The month in number format (ex. 06 for June)
        $monthName      = date("F", mktime(0, 0, 0, $monthNum, 10));		// month in word format (ex. June/July/August)
        
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        
        $query          -> select($db->quoteName('Sidereal'));
        $query          -> from($db->quoteName('#__sidereal_1'));
        $query          -> where($db->quoteName('Month').'='.$db->quote($monthName).'AND'.
                                 $db->quoteName('Date')."=".$db->quote($dob[1]));
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
                $query      ->where($db->quoteName('Year').'='.$db->quote($dob[2]).' AND '.
                                    $db->quote('leap').'='.'*');
            }
            else
            {
                $query      ->select($db->quoteName('corr_time'));
                $query      ->from($db->quoteName('#__sidereal_2'));
                $query      ->where($db->quoteName('Year').'='.$db->quote($dob[2]));
                //$query_sideyear			= mysqli_query($con, "SELECT corr_time FROM jv_sidereal_2 WHERE Year='".$dob_split[0]."'");
            }
            $db                 ->setQuery($query);
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
                       
            return $date->format('H:i:s');
        }
        
    }
    public function getLmt()
    {
        $lon        = explode(":", $this->lon);
        $lat        = explode(":", $this->lat);
        
    }
}
?>