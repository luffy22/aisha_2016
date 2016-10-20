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
                include_once "/home/astroxou/php/Net/GeoIP.php";
                $geoip = Net_GeoIP::getInstance("/home/astroxou/php/Net/GeoLiteCity.dat");
                $ip    = '157.55.39.123';  // ip address
                //$ip = $_SERVER['REMOTE_ADDR'];        // uncomment this ip on server
                $location 		= $geoip->lookupLocation($ip);
                $info                   = $location->countryCode;
                $country                = $location->countryName;
                if($info == "US")
                {
                    $results   = array('country'=>$country,'amount'=>'10.00','currency'=>'USD','curr_code'=>'&#36;', 'curr_full'=>'United States Dollar');
                }
                else if($info == "IN"||$info== 'LK'||$info=='NP'||$info=='TH'||$info=='MY'||$info=='MV')
                {
                    $results   = array('country'=>$country,'amount'=>'300.00', 'currency'=>'INR','curr_code'=>'&#8377;','curr_full'=>'Indian Rupees');
                }
                else if($info=='UK')
                {
                    $results   = array('country'=>$country,'amount'=>'7.50','currency'=>'GBP','curr_code'=>'&#8356;','curr_full'=>'British Pound');
                }
                else if($info=='NZ')
                {
                    $results   = array('country'=>$country,'amount'=>'15.00','currency'=>'NZD','curr_code'=>'&#36;', 'curr_full'=>'New Zealand Dollar');
                }
                else if($info=='CA')
                {
                    $results   = array('country'=>$country,'amount'=>'10.00','currency'=>'CAD','curr_code'=>'&#36;', 'curr_full'=>'Canadian Dollar');
                }
                else if($info=='SG')
                {
                    $results   = array('country'=>$country,'amount'=>'15.00','currency'=>'SGD','curr_code'=>'&#36;','curr_full'=>'Singapore Dollar');
                }
                else if($info=='AU')
                {
                    $results   = array('country'=>$country,'amount'=>'15.00','currency'=>'AUD','curr_code'=>'&#36;', 'curr_full'=>'Australian Dollar');
                }
                else if($info=='FR'||$info=='DE'||$info=='IE'||$info=='NL'||$info=='CR'||$info=='BE'
                        ||$info=='GR'||$info=='IT'||$info=='PT'||$info=='ES'||$info=='MT'||$info=='LV'||$info=='TR')
                {
                    $results = array('country'=>$country,'amount'=>'10.00','currency'=>'EUR','curr_code'=>'&#8364;', 'curr_full'=>'European Union Euro');
                }
                else if($info =='RU')
                {
                    $results = array('country'=>$country,'amount'=>'500.00','currency'=>'RUB','curr_code'=>'&#8364;', 'curr_full'=>'Russian Ruble');
                }
                 else
                {
                    $results   = array('country'=>$country,'amount'=>'7.00', 'currency'=>'USD','curr_code'=>'&#36;','curr_full'=>'United States Dollar');
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
           $fields          = array($db->quoteName('paid').'= '.$db->quote('Yes'),$db->quoteName('token').' = '.$db->quote($token),
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
           $query->clear();        // unset all variables
           $query       ->select($db->quoteName(array('a.name','a.email','a.username',
                                    'b.membership','c.amount','c.currency','c.paid','c.location',
                                    'c.token','c.payment_id')))
                            ->from($db->quoteName('#__users','a'))
                            ->join('INNER', $db->quoteName('#__user_astrologer','b').' ON (' . $db->quoteName('a.id').' = '.$db->quoteName('b.UserID') . ')')
                            ->join('INNER', $db->quoteName('#__user_finance', 'c').' ON ('.$db->quoteName('a.id').' = '.$db->quoteName('c.UserId').')')
                            ->where($db->quoteName('a.id').' = '.$db->quote($uid));
            $db                  ->setQuery($query);
            $details                 = $db->loadAssoc();
            $bcc                = 'kopnite@gmail.com';
            $subject            = "Astrologer Registration Number: ".substr($details['token'],6)."<br/>";
            $body               = "Dear ".$details['name'].",<br/>";
            $body               .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Welcome to Astro Isha. Your registration has been successful. We can confirm that 
                                        your Online Payment has been successful. You can login via: <a href='https://www.astroisha.com/login'>Login Page</a> and change your details as well as update 						financial information to start receiving payments. Alternatively you can also 
                                        email them to admin@astroisha.com by filling the attachment form provided or sending the attachment via whatsapp on +91-9727841461.<br/><br/>";
            $body                  .= "<div style='align:center;font-size:15px'><strong>Payment Details</strong></div><br/>";
            $body                  .= "Astrologer Registration Number: ".substr($details['token'],6)."<br/>";
            $body                  .= "Name: ".$details['name']."<br/>";
            $body                  .= "Email: ".$details['email']."<br/>";
            $body                  .= "Username: ".$details['username']."<br/>";
            $body                  .= "Type Of Membership: ".$details['membership']."<br/>";
            $body                  .= "Payment Completed: ".$details['paid']."<br/>";
            $body                  .= "Amount: ".$details['amount']." ".$details['currency']."<br/>";
            $body                  .= "Payment ID: ".$details['payment_id']."<br/><br/>";
            $body               .= "<span style='color:red'>Kindly Note: Do not ever share your bank password, ATM Pin or 
                                        other private information with us. We only require your Account Number, Name, IBAN and Swift Code or Paypal ID/Email for money transfer.</span><br/>";
            $body               .= "<br/><div style='align:right'>Admin At Astro Isha,<br/>Rohan Desai</div>"; 
            $mailer             = JFactory::getMailer();
            $config             = JFactory::getConfig();
            $sender             = array( 
                                            $config->get( 'mailfrom' ),
                                            $config->get( 'fromname' ) 
                                        );

            $mailer             ->setSender($sender);
            $mailer             ->addRecipient($details['email']);
            $mailer             ->addBCC($bcc, 'Rohan Desai');
            $mailer             ->setSubject($subject);
            $mailer             ->isHTML(true);
            $mailer             ->Encoding = 'base64';
            $mailer             ->setBody($body);

            $send = $mailer->Send();
             if ( $send !== true ) {
                $msg    =  'Error sending email: ' . $send->__toString();
                $msgType = "error";
                $link   = JUri::base().'dashboard?payment=failure';
                $app->redirect($link, $msg,$msgType);
            } else {
                $msg    = "Check Your Email For Confirmation.";
                $msgType    = "success";
                $link   = JUri::base().'dashboard?payment=success';
               $app->redirect($link); 
            }
        }
        else
        {
             $query->clear();unset($fields);unset($conditions);
            $fields          = array($db->quoteName('paid').'= '.$db->quote('No'),$db->quoteName('token').' = '.$db->quote($token));
           $conditions      = array($db->quoteName('UserId').' = '.$db->quote($uid));
           $query->update($db->quoteName('#__user_finance'))->set($fields)->where($conditions);
           $db->setQuery($query);$db->execute();
            $query->clear();        // unset all variables
            $query       ->select($db->quoteName(array('a.name','a.email','a.username',
                                    'b.membership','c.amount','c.currency','c.paid','c.location',
                                    'c.token','c.payment_id')))
                            ->from($db->quoteName('#__users','a'))
                            ->join('INNER', $db->quoteName('#__user_astrologer','b').' ON (' . $db->quoteName('a.id').' = '.$db->quoteName('b.UserID') . ')')
                            ->join('INNER', $db->quoteName('#__user_finance', 'c').' ON ('.$db->quoteName('a.id').' = '.$db->quoteName('c.UserId').')')
                            ->where($db->quoteName('a.id').' = '.$db->quote($uid));
            $db                  ->setQuery($query);
            $details                 = $db->loadAssoc();
            $paylink        = "https://www.paypal.me/AstroIsha/".$details['amount'].$details['currency'];
            $payhref        = "<a href=".$paylink.">AstroIsha Paypal</a>";
            // if status is failure show payment_failure
            $bcc                = 'kopnite@gmail.com';
            $subject            = "Astrologer Registration Number: ".substr($details['token'],6);
            $body               = "<br/>Dear ".$details['name'].",<br/>";
            $body               .= "&nbsp;&nbsp;&nbsp;Your Online Payment has failed. We have initiated your Account as a Free Member. You can login via: <a href='https://www.astroisha.com/login'>Login Page</a> and change your details or alternatively you can also email your details to admin@astroisha.com by filling the attachment form provided or sending the attachment via whatsapp on +91-9727841461.
                                    If you wish to be a paid member then kindly submit ".$details['amount']." ".$details['currency']." to URL: ".$payhref.
                                    " Alternatively you can use direct transfer to get Paid Membership. Details of Direct Transfer are provided below. 
                                    Be sure to notify us at admin@astroisha.com with Registration Number and Email Address
                                    when payment via Paypal or Direct Transfer is done to avail Paid Services.<br/><br/>";
            $body                  .= "<div style='align:center;font-size:15px'><strong>Payment Details</strong></div><br/>";
            $body                  .= "Astrologer Registration Number: ".substr($details['token'],6)."<br/>";
            $body                  .= "Name: ".$details['name']."<br/>";
            $body                  .= "Email: ".$details['email']."<br/>";
            $body                  .= "Username: ".$details['username']."<br/>";
            $body                  .= "Type Of Membership: Free<br/>";
            $body                  .= "Payment Completed: ".$details['paid']."<br/>";
            $body                  .= "Amount To Be Paid: ".$details['amount']." ".$details['currency']."<br/><br/>";
            $body               .= "Below are Our Bank Details For Direct Transfer<br/>";
            $body               .= "Payable to: Astro Isha<br/> 
                                    Account Number: 915020051554614<br/> 
                                    Axis Bank MANINAGAR, AHMEDABAD <br/>
                                    Address:<br/>GROUND FLOOR, BUSINESS SQUARE BUILDING,<br/>NR. KRISHNABAUG CHAR RISTA<br/> AHMEDABAD 380008<br/>";
            $body               .= "Swift Code: AXISINBB080<br/><br/>";
            $body               .= "<span style='color:red'>Kindly Note: Do not ever share your bank password, ATM Pin or 
                                        other private information with us. We only require your Account Number, Name, and Internation Swift Code or Paypal ID/Email for money transfer in case you decide to opt for Paid Membership.</span><br/>";
            $body               .= "<br/><div style='align:right'>Admin At Astro Isha,<br/>Rohan Desai</div>"; 
            $mailer             = JFactory::getMailer();
            $config             = JFactory::getConfig();
            $sender             = array( 
                                            $config->get( 'mailfrom' ),
                                            $config->get( 'fromname' ) 
                                        );

            $mailer             ->setSender($sender);
            $mailer             ->addRecipient($details['email']);
            $mailer             ->addBCC($bcc, 'Rohan Desai');
            $mailer             ->setSubject($subject);
            $mailer             ->isHTML(true);
            $mailer             ->Encoding = 'base64';
            $mailer             ->setBody($body);

            $send = $mailer->Send();
             if ( $send !== true ) {
                $msg    =  'Error sending email. Your Online Payment has failed';
                $msgType = "error";
                $link   = JUri::base().'dashboard?payment=failure';
                $app->redirect($link, $msg,$msgType);
            } else {
                $msg    = "Online Payment Failed. Please check Email for more details";
                $msgType    = "success";
                $link   = JUri::base().'dashboard?payment=failure';
               $app->redirect($link); 
            }
        }
    }
}
