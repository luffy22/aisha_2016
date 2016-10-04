<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
//Import filesystem libraries. Perhaps not necessary, but does not hurt
jimport('joomla.filesystem.file');
class ExtendedProfileModelDashboard extends JModelItem
{
    public function getData()
    {
        $user = JFactory::getUser();
        if($user->guest)
         {
            $location   = JURi::base()."login";
            $mainframes = JFactory::getApplication();
            $link=  JURI::base().'login';
            $msg = "Please Login";

            $mainframes->redirect($link, $msg);
         }
        $id   = $user->id;$name = $user->name;  
        // get the data
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          ->select($db->quoteName(array('membership','UserId')));
        $query          ->from($db->quoteName('#__user_astrologer'));
        $query          ->where($db->quoteName('UserId').' = '.$db->quote($id));
        $db             ->setQuery($query);
        $db->execute();
        $row            = $db->getNumRows();
        $result         = $db->loadAssoc();
        if($row > 0 && $result['membership'] == 'free')
        {
            $query          ->clear();
            $query          ->select($db->quoteName(array('a.id','a.name','a.username','a.email', 
                                        'b.membership','b.img_1','b.img_1_id','b.addr_1','b.addr_2','b.city',
                                        'b.state','b.country','b.postcode','b.phone','b.mobile','b.whatsapp','b.website',
                                        'b.info','b.profile_status')));
            $query          ->from($db->quoteName('#__users', 'a'));
            $query          ->join('INNER', $db->quoteName('#__user_astrologer','b'). ' ON (' . $db->quoteName('a.id').' = '.$db->quoteName('b.UserId') . ')');
            $query          ->where($db->quoteName('a.id').' = '.$db->quote($id));
            $db             ->setQuery($query);
            $results =      $db->loadAssoc();
        }
        else if($row > 0 && $result['membership'] == 'paid')
        {
            $query          ->clear();
            $query          ->select($db->quoteName(array('a.id','a.name','a.username','a.email', 
                                        'b.membership','b.img_1','b.img_1_id','b.addr_1','b.addr_2','b.city',
                                        'b.state','b.country','b.postcode','b.phone','b.mobile','b.whatsapp','b.website',
                                        'b.info','b.profile_status','c.acc_holder_name','c.acc_number','c.acc_bank_name',
                                        'c.acc_bank_addr','c.acc_iban','c.acc_swift_code','c.acc_ifsc','c.acc_paypalid')));
            $query          ->from($db->quoteName('#__users', 'a'));
            $query          ->join('INNER', $db->quoteName('#__user_astrologer','b'). ' ON (' . $db->quoteName('a.id').' = '.$db->quoteName('b.UserId') . ')');
            $query          ->join('INNER', $db->quoteName('#__user_finance','c').' ON ('.$db->quoteName('a.id').' = '.$db->quoteName('c.UserId').')');
            $query          ->where($db->quoteName('a.id').' = '.$db->quote($id));
            $db             ->setQuery($query);
            $results =      $db->loadAssoc();
        }
        else
        {
            
            try
            {
                $ip    = '157.55.39.123';  // ip address
                //$ip = $_SERVER['REMOTE_ADDR'];        // uncomment this ip on server
                $info = geoip_country_code_by_name($ip);
                if($info == "US")
                {
                    $results   = array('amount'=>'10.00','currency'=>'USD','curr_code'=>'&#36;', 'curr_full'=>'United States Dollar');
                }
                else if($info == "IN")
                {
                    $results   = array('amount'=>'300.00', 'currency'=>'INR','curr_code'=>'&#8377;','curr_full'=>'Indian Rupees');
                }
                else if($info=='UK')
                {
                    $results   = array('amount'=>'7.00','currency'=>'GBP','curr_code'=>'&#8356;','curr_full'=>'British Pound');
                }
                 
            }
            catch(Exception $e)
            {
                $results =  array('error'=> 'Data not showing');
            }
        }
        return $results;
    }
}