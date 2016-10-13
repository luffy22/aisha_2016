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
        if($row > 0 && ($result['membership'] == 'free'||$result['membership']=='unpaid'))
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
                else if($info == "IN"||$info== 'LK'||$info=='NP'||$info=='TH'||$info=='MY'||$info=='MV')
                {
                    $results   = array('amount'=>'300.00', 'currency'=>'INR','curr_code'=>'&#8377;','curr_full'=>'Indian Rupees');
                }
                else if($info=='UK')
                {
                    $results   = array('amount'=>'7.50','currency'=>'GBP','curr_code'=>'&#8356;','curr_full'=>'British Pound');
                }
                else if($info=='NZ')
                {
                    $results   = array('amount'=>'15.00','currency'=>'NZD','curr_code'=>'&#36;', 'curr_full'=>'New Zealand Dollar');
                }
                else if($info=='CA')
                {
                    $results   = array('amount'=>'10.00','currency'=>'CAD','curr_code'=>'&#36;', 'curr_full'=>'Canadian Dollar');
                }
                else if($info=='SG')
                {
                    $results   = array('amount'=>'15.00','currency'=>'SGD','curr_code'=>'&#36;','curr_full'=>'Singapore Dollar');
                }
                else if($info=='AU')
                {
                    $results   = array('amount'=>'15.00','currency'=>'AUD','curr_code'=>'&#36;', 'curr_full'=>'Australian Dollar');
                }
                else if($info=='FR'||$info=='DE'||$info=='IE'||$info=='NL'||$info=='CR'||$info=='BE'
                        ||$info=='GR'||$info=='IT'||$info=='PT'||$info=='ES'||$info=='MT'||$info=='LV'||$info=='TR')
                {
                    $results = array('amount'=>'10.00','currency'=>'EUR','curr_code'=>'&#8364;', 'curr_full'=>'European Union Euro');
                }
                 else
                {
                    $results   = array('amount'=>'7.00', 'currency'=>'USD','curr_code'=>'&#36;','curr_full'=>'United States Dollar');
                }
            }
            catch(Exception $e)
            {
                $results =  array('error'=> 'Data not showing');
            }
        }
        return $results;
    }
    public function authorizePayment($details)
    {
        $email      = $details['email'];$pay_id     = $details['pay_id'];$uid   = $details['uid'];
        $token      = $details['token'];$status     = $details['status'];
        $db         = JFactory::getDbo();  // Get db connection
        $query      = $db->getQuery(true);
        $app        = JFactory::getApplication();
        if($status == 'success')
        {
           $fields          = array($db->quoteName('membership').' = '.$db->quote('paid'));
           $conditions      = array($db->quoteName('UserId') . ' = '.$db->quote($uid));
           $query->update($db->quoteName('#__user_astrologer'))->set($fields)->where($conditions);
           $db->setQuery($query);$result = $db->execute();
           unset($result);$query->clear();unset($fields);unset($conditions);            // unset all variables
           $fields          = array($db->quoteName('paid').'= '.$db->quote('yes'),$db->quoteName('token').' = '.$db->quote($token),
                                    $db->quoteName('payment_id').' = '.$db->quote($pay_id),
                                    $db->quoteName('acc_paypalid').' = '.$db->quote($email));
           $conditions      = array($db->quoteName('UserId').' = '.$db->quote($uid));
           $query->update($db->quoteName('#__user_finance'))->set($fields)->where($conditions);
           $db->setQuery($query);$result = $db->execute();
           unset($result);$query->clear();unset($fields);unset($conditions);            // unset all variables
           $fields          = array($db->quoteName('group_id').'='.$db->quote(10));
           $conditions      = array($db->quoteName('user_id').' = '.$db->quote($uid));
           $query->update($db->quoteName('#__user_usergroup_map'))->set($fields)->where($conditions);
           $db->setQuery($query);$result = $db->execute(); 
           if($result)
           {
               $link   = JUri::base().'dashboard?payment=success';
               $app->redirect($link);
           }
           else
           {
               $link   = JUri::base().'dashboard?payment=failure';
               $app->redirect($link);
           }
        }
        else
        {
            // if status is failure show payment_failure
            $link   = JUri::base().'dashboard?payment=failure';
            $app->redirect($link);
        }
    }
}