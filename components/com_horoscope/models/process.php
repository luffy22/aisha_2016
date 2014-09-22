<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class HoroscopeModelProcess extends JModelItem
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
        
        return $this->getSiderealTime();
      
    }
    // Method to get the sidereal Time
    public function getSiderealTime()
    {
        $dob                = explode("/",$this->dob);
        $monthNum           = $dob[1];  // The month in number format (ex. 06 for June)
        $monthName          = date("F", mktime(0, 0, 0, $monthNum, 10));		// month in word format (ex. June/July/August)
        
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
            $date					->format('H:i:s');
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
            $db             ->setQuery($query);
            $count          = count($db->loadResult());
            $row            =$db->loadAssoc();
            return $count;
        }
                
    }
}
?>