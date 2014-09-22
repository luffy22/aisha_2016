<?php
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/controller.php';
/**
 * Registration controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.6
 */
class HoroscopeControllerProcess extends HoroscopeController
{
    public function findlagna()
    {
        if(isset($_POST['lagnasubmit']))
        {
            $fname  = $_POST['fname'];$gender   = $_POST['gender'];$dob     = $_POST['dob'];
            $tob    = $_POST['lagna_hr'].":".$_POST['lagna_min'].":".$_POST['lagna_sec'].":".$_POST['lagna_time'];
            $lon    = $_POST['lon_deg'].":".$_POST['lon_min'].":".$_POST['lon_dir'];
            $lat    = $_POST['lat_deg'].":".$_POST['lat_min'].":".$_POST['lat_dir'];$timezone   = $_POST['lagna_timezone'];
            
            $user_details   = array(
                                    'fname'=>$fname,'gender'=>$gender,'dob'=>$dob,
                                    'tob'=>$tob,'lon'=>$lon,'lat'=>$lat,'tz'=>$timezone
                                    );
            /*echo "<pre>";
            print_r($user_details);
            echo "</pre>";*/
            $model          = &$this->getModel('process');  // Add the array to model
            $model          ->getLagna($user_details);
        }
        else
        {
            echo JRoute::_('index.php');
        }
        //$model          = &$this->getModel('process');  // Add the array to model
        //$model          ->getLagna();
        //echo "calls";
    }
   
}
?>
