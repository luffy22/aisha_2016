<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
//Import filesystem libraries. Perhaps not necessary, but does not hurt
jimport('joomla.filesystem.file');
class ExtendedProfileModelExtendedProfile extends JModelItem
{
    public function redirectLink($url)
    {
        header('Location: '.$url);
    }
    public function getData()
    {
        $user = JFactory::getUser();
        $id   = $user->id;$name = $user->name;       
        // get the data
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          ->select($db->quoteName(array('UserId','membership','img_1','img_1_id',
                                     'addr_1','addr_2', 'city','state','country',
                                    'postcode','phone','mobile','whatsapp','website', 'info','profile_status')));
        $query          ->from($db->quoteName('#__user_astrologer'));
        $query          ->where($db->quoteName('UserId').' = '.$db->quote($id));
        $db             ->setQuery($query);
        $astro          = $db->loadAssoc();
        return $astro;
    }
    public function saveUser($data)
    {
        //print_r($data);exit;
        $user           = JFactory::getUser();
        $id             = $user->id;
        $membership     = ucfirst($data['membership']);   // astrologer membership type free/paid
        $amount         = $data['amount'];$curr = $data['currency'];$country=  $data['country'];
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          ->select(array('UserId','membership'));
        $query          ->from($db->quoteName('#__user_astrologer'));
        $query          ->where($db->quoteName('UserId').' = '.$db->quote($id));
        $db             ->setQuery($query);$db->execute();
        $row            = $db->getNumRows();    
        $app            = JFactory::getApplication();
        
        if($row > 0)
        {
                $link       = JURI::base().'dashboard?data=double';
                $msg        = "Data Already Exists..";
                $app        ->redirect($link,$msg,$msgType='error');
        }
        else
        {
            $query          ->clear();
            if($membership=='Paid')
            {
                $membership     = "Unpaid";
                $columns        = array('UserId','membership');
                $values         = array($db->quote($id),$db->quote($membership));
            }
            else
            {
                $columns        = array('UserId','membership');
                $values         = array($db->quote($id),$db->quote($membership));
            }
            $query
            ->insert($db->quoteName('#__user_astrologer'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
            // Set the query using our newly populated query object and execute it
            $db             ->setQuery($query);
            $result          = $db->query();
            if($membership=='Unpaid')
            {
                $token              = uniqid('token_');
                $query      ->clear();
                $columns    = array('UserId','token','amount','currency','location');
                $values     = array($db->quote($id),$db->quote($token),$db->quote($amount),$db->quote($curr),$db->quote($country));
                $query
                    ->insert($db->quoteName('#__user_finance'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
                $db             ->setQuery($query);$db->query();
                $query  ->clear();
                $query          ->select(array('a.id','a.name','a.email','b.token','b.currency','b.amount'));
                $query          ->from($db->quoteName('#__users','a'));
                $query          ->join('INNER', $db->quoteName('#__user_finance','b'). ' ON (' . $db->quoteName('a.id').' = '.$db->quoteName('b.UserId') . ')');
                $query          ->where($db->quoteName('id').' = '.$db->quote($id));
                $db             ->setQuery($query);
                $result         = $db->loadAssoc();
                //print_r($result);exit;
                if($data['pay_type'] == 'online'&& $data['currency']=='INR')
                {
                    $link   = JUri::base().'ccavenue/nonseam/ccavenue_astrologer.php?id='.$id.'&token='.$result['token'].'&name='.$result['name'].'&email='.$result['email'].'&curr='.$result['currency'].'&amount='.$result['amount']; 
                    $app->redirect($link);
                }
                else if($data['pay_type'] == 'online'&& $data['currency']!=='INR')
                {
                    $link   = JUri::base().'vendor/paypal_astro.php?id='.$id.'&token='.$result['token'].'&name='.$result['name'].'&email='.$result['email'].'&curr='.$curr.'&amount='.$amount;
                    $app->redirect($link);
                }
                else if($data['pay_type'] == 'transfer')
                {
                    $query->clear();        // unset all variables
                    $query       ->select($db->quoteName(array('a.name','a.email','a.username',
                                    'b.membership','b.number','c.amount','c.currency','c.paid','c.location')))
                            ->from($db->quoteName('#__users','a'))
                            ->join('INNER', $db->quoteName('#__user_astrologer','b').' ON (' . $db->quoteName('a.id').' = '.$db->quoteName('b.UserID') . ')')
                            ->join('INNER', $db->quoteName('#__user_finance', 'c').' ON ('.$db->quoteName('a.id').' = '.$db->quoteName('c.UserId').')')
                            ->where($db->quoteName('a.id').' = '.$db->quote($id));
                    $db                  ->setQuery($query);
                    $details        = $db->loadAssoc();

                    $reg_number         = "AS00".$details['number']."00";
                    $bcc                = 'kopnite@gmail.com';
                    $subject            = "AstroIsha Register ID: ".$reg_number;
                    $body               = "<br/>Dear ".$details['name'].",<br/>";
                    $body               .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Welcome to Astro Isha. Your Free Account has been activated. You can login via: <a href='https://www.astroisha.com/login'>Login Page</a> and change your details.
                                            Alternatively you can also email them to admin@astroisha.com by filling the attachment form provided or sending the attachment via whatsapp on +91-9727841461.
                                            Once the direct transfer request has been done kindly reply back at 
                                            admin@astroisha.com with Registration Number and Email and we would open your Paid Account 
                                            whereby you can accept Online Payments and Orders. Bank Details for Direct Transfer are provided below.<br/><br/>";
                    $body               .= "<div style='align:center;font-size:15px'><strong>Account Details</strong></div><br/>";
                    $body               .= "Astrologer Registration Number: ".$reg_number."<br/>";
                    $body               .= "Name: ".$details['name']."<br/>";
                    $body               .= "Email: ".$details['email']."<br/>";
                    $body               .= "Username: ".$details['username']."<br/>";
                    $body               .= "Type Of Membership: Free<br/>";
                    $body                  .= "Payment Completed: ".$details['paid']."<br/>";
                    $body                  .= "Amount To Be Paid: ".$details['amount']." ".$details['currency']."<br/><br/>";
                    $body               .= "<strong>Below are Our Bank Details For Direct Transfer</strong><br/>";
                    $body               .= "Payable to: Astro Isha<br/> 
                                            Account Number: 915020051554614<br/> 
                                            Axis Bank MANINAGAR, AHMEDABAD <br/>
                                            Address:<br/>GROUND FLOOR, BUSINESS SQUARE BUILDING,<br/>NR. KRISHNABAUG CHAR RISTA<br/> AHMEDABAD 380008<br/>";
                    if($data['currency']=='INR')
                    {
                        $body               .= "MICR Code: 380211005<br/>";
                        $body               .= "IFSC Code: UTIB0000080<br/><br/>";
                        $body               .= "<span style='color:red'>Kindly Note: Do not ever share your Bank Passwords, ATM Pin or 
                                        other Private Information with us. We only require your Account Number or Card Number, Name, and IFSC Code for money transfer in case you decide to opt for Paid Membership.</span><br/>";
                    }
                    else
                    {
                        $body               .= "Swift Code: AXISINBB080<br/><br/>";
                        $body               .= "<span style='color:red'>Kindly Note: Do not ever share your Bank Passwords, ATM Pin or 
                                                other Private Information with us. We only require your Account Number, Name, and Swift Code or Paypal ID/Email for money transfer in case you decide to opt for Paid Membership.</span><br/>";
                    }
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
                    if ( $send !== true ) 
                    {
                        $msg    =  'Error sending email: ' . $send->__toString();
                        $msgType = "error";
                        $link   = JUri::base().'dashboard?freeacc=success';
                        $app->redirect($link, $msg,$msgType);
                    } else 
                    {
                        $msg    = "Check Your Email For Confirmation.";
                        $msgType    = "success";
                        $link   = JUri::base().'dashboard?payment=success';
                        $app->redirect($link); 
                    }
                }
                else if($data['pay_type'] == 'cheque')
                {
                    $query->clear();        // unset all variables
                    $query       ->select($db->quoteName(array('a.name','a.email','a.username',
                                    'b.membership','b.number','c.amount','c.currency','c.paid','c.location')))
                            ->from($db->quoteName('#__users','a'))
                            ->join('INNER', $db->quoteName('#__user_astrologer','b').' ON (' . $db->quoteName('a.id').' = '.$db->quoteName('b.UserID') . ')')
                            ->join('INNER', $db->quoteName('#__user_finance', 'c').' ON ('.$db->quoteName('a.id').' = '.$db->quoteName('c.UserId').')')
                            ->where($db->quoteName('a.id').' = '.$db->quote($id));
                    $db                  ->setQuery($query);
                    $details        = $db->loadAssoc();

                    $reg_number         = "AS00".$details['number']."00";
                    $bcc                = 'kopnite@gmail.com';
                    $subject            = "AstroIsha Register ID: ".$reg_number;
                    $body               = "<br/>Dear ".$details['name'].",<br/>";
                    $body               .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Welcome to Astro Isha. Your Free Account has been activated. You can login via: <a href='https://www.astroisha.com/login'>Login Page</a> and change your details.
                                            Alternatively you can also email them to admin@astroisha.com by filling the attachment form provided or sending the attachment via whatsapp on +91-9727841461.
                                            If you wish to pay by Cheque then write a Cheque to <strong>Astro Isha</strong> and submit it to your nearest Axis Bank. 
                                            Alternatively you can use direct transfer to send money. Once payment is done kindly reply back at 
                                            admin@astroisha.com with Registration Number and Email and we would open your Paid Account 
                                            whereby you can accept Online Payments and Orders. Bank Details for Direct Transfer are provided below.<br/><br/>";
                    $body               .= "<div style='align:center;font-size:15px'><strong>Account Details</strong></div><br/>";
                    $body               .= "Astrologer Registration Number: ".$reg_number."<br/>";
                    $body               .= "Name: ".$details['name']."<br/>";
                    $body               .= "Email: ".$details['email']."<br/>";
                    $body               .= "Username: ".$details['username']."<br/>";
                    $body               .= "Type Of Membership: Free<br/>";
                    $body                  .= "Payment Completed: ".$details['paid']."<br/>";
                    $body                  .= "Amount To Be Paid: ".$details['amount']." ".$details['currency']."<br/><br/>";
                    $body               .= "<strong>Below are Our Bank Details For Direct Transfer</strong><br/>";
                    $body               .= "Payable to: Astro Isha<br/> 
                                            Account Number: 915020051554614<br/> 
                                            Axis Bank MANINAGAR, AHMEDABAD <br/>
                                            Address:<br/>GROUND FLOOR, BUSINESS SQUARE BUILDING,<br/>NR. KRISHNABAUG CHAR RISTA<br/> AHMEDABAD 380008<br/>";
                    if($data['currency']=='INR')
                    {
                        $body               .= "MICR Code: 380211005<br/>";
                        $body               .= "IFSC Code: UTIB0000080<br/><br/>";
                        $body               .= "<span style='color:red'>Kindly Note: Do not ever share your Bank Passwords, ATM Pin or 
                                        other Private Information with us. We only require your Account Number or Card Number, Name, and IFSC Code for money transfer in case you decide to opt for Paid Membership.</span><br/>";
                    }
                    else
                    {
                        $body               .= "Swift Code: AXISINBB080<br/><br/>";
                        $body               .= "<span style='color:red'>Kindly Note: Do not ever share your Bank Passwords, ATM Pin or 
                                                other Private Information with us. We only require your Account Number, Name, and Swift Code or Paypal ID/Email for money transfer in case you decide to opt for Paid Membership.</span><br/>";
                    }
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
                    if ( $send !== true ) 
                    {
                        $msg    =  'Error sending email: ' . $send->__toString();
                        $msgType = "error";
                        $link   = JUri::base().'dashboard?freeacc=success';
                        $app->redirect($link, $msg,$msgType);
                    } else 
                    {
                        $msg    = "Check Your Email For Confirmation.";
                        $msgType    = "success";
                        $link   = JUri::base().'dashboard?freeacc=success';
                        $app->redirect($link); 
                    }
                }
                else
                {
                    $link   = JUri::base().'vendor/paypal_astro.php?id='.$id.'&token='.$result['token'].'&name='.$result['name'].'&email='.$result['email'].'&curr='.$curr.'&amount='.$amount;
                    $app->redirect($link);
                }
            }
            else
            { 
                $query->clear();        // unset all variables
                $query          ->select($db->quoteName(array('a.name','a.email','a.username',
                                    'b.membership', 'b.number')))
                                ->from($db->quoteName('#__users','a'))
                                ->join('INNER', $db->quoteName('#__user_astrologer','b').' ON (' . $db->quoteName('a.id').' = '.$db->quoteName('b.UserID') . ')')
                                ->where($db->quoteName('a.id').' = '.$db->quote($id));
                $db             ->setQuery($query);
                $details        = $db->loadAssoc();
                
                $reg_number         = "AS00".$details['number']."00";
                $bcc                = 'kopnite@gmail.com';
                $subject            = "AstroIsha Register ID: ".$reg_number;
                $body               = "<br/>Dear ".$details['name'].",<br/>";
                $body               .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Welcome to Astro Isha. Your Free Account has been activated. You can login via: <a href='https://www.astroisha.com/login'>Login Page</a> and change your details.
                                        Alternatively you can also email them to admin@astroisha.com by filling the attachment form provided or sending the attachment via whatsapp on +91-9727841461.<br/><br/>";
                $body               .= "<div style='align:center;font-size:15px'><strong>Account Details</strong></div><br/>";
                $body               .= "Astrologer Registration Number: ".$reg_number."<br/>";
                $body               .= "Name: ".$details['name']."<br/>";
                $body               .= "Email: ".$details['email']."<br/>";
                $body               .= "Username: ".$details['username']."<br/>";
                $body               .= "Type Of Membership: ".$details['membership']."<br/><br/>";
                $body               .= "<span style='color:red'>Kindly Note: Do not ever share your Bank Passwords, ATM Pin or 
                                            other Private Information with us. We only require your Account Number, Name, IBAN and Swift Code or Paypal ID/Email for money transfer.</span><br/>";
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
                    $link   = JUri::base().'dashboard?freeacc=success';
                    $app->redirect($link, $msg,$msgType);
                } else {
                    $msg    = "Check Your Email For Confirmation.";
                    $msgType    = "success";
                    $link   = JUri::base().'dashboard?payment=success';
                   $app->redirect($link); 
                }
                    
                }
        }
    }
    public function updateUser($data)
    {
        //print_r($data);exit;
        $userid         = $data['userid'];
        //echo $userid;exit;
        $gender         = $data['gender'];$dob      = $data['dob'];
        $tob            = $data['tob'];$pob         = $data['pob'];$astro       = $data['astro'];
        // Get db connection
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
 
        $fields         = array($db->quoteName('gender').' = '.$db->quote($gender),
                                $db->quoteName('dob').' = '.$db->quote($dob),
                                $db->quoteName('tob').' = '.$db->quote($tob),
                                $db->quoteName('pob').' = '.$db->quote($pob),
                                $db->quoteName('astrologer').' = '.$db->quote($astro));
        $conditions     = array($db->quoteName('UserId').' = '.$db->quote($userid));
        
        $query->update($db->quoteName('#__user_extended'))->set($fields)->where($conditions);
        $db->setQuery($query);
 
        $result = $db->execute();
        return $result;
    }
    public function saveAstro($details)
    {
        //print_r($details);exit;
        $ext            = JFile::getExt($details['img_name']);
        $uniq_name      = 'img_'.date('Y-m-d-H-i-s').'_'.uniqid().".".$ext;
        
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $src            = $details['tmp_name']; //echo $src."<br/>";
        $dest           = JPATH_BASE.DS."images". DS ."profiles".DS.$uniq_name;
        //echo $dest;exit;
        $id             = $details['id'];$img_name      = $details['img_name'];
        $img_id         = $uniq_name;    $addr1         = $details['addr1'];
        $addr2          = $details['addr2'];$city       = $details['city'];
        $state          = $details['state'];$country    = $details['country'];
        $pcode          = $details['pcode'];$phone      = $details['phone'];
        $mobile         = $details['mobile'];$whatsapp  = $details['whatsapp'];
        $website        = $details['website'];$info     = $details['info'];$status  = 'visible';
        $upload         = JFile::upload($src, $dest);
        if($upload)
        {
            $fields         = array(
                                $db->quoteName('img_1').'='.$db->quote($img_name),
                                $db->quoteName('img_1_id').'='.$db->quote($img_id),
                                $db->quoteName('addr_1').'='.$db->quote($addr1),
                                $db->quoteName('addr_2').'='.$db->quote($addr2),
                                $db->quoteName('city').'='.$db->quote($city),
                                $db->quoteName('state').'='.$db->quote($state),
                                $db->quoteName('country').'='.$db->quote($country),
                                $db->quoteName('postcode').'='.$db->quote($pcode),
                                $db->quoteName('phone').'='.$db->quote($phone),
                                $db->quoteName('mobile').'='.$db->quote($mobile),
                                $db->quoteName('whatsapp').'='.$db->quote($whatsapp),
                                $db->quoteName('website').'='.$db->quote($website),
                                $db->quoteName('info').'='.$db->quote($info),
                                );
            $conditions = array(
                                    $db->quoteName('UserId') . ' = '.$db->quote($id)
                                );
            $query->update($db->quoteName('#__user_astrologer'))->set($fields)->where($conditions);

            $db->setQuery($query);
            $result          = $db->query();
            $app = JFactory::getApplication();
            $link=  JURI::base().'dashboard';
            if($result)
            {
                $msg = "Data Added Successfully..";
                
            }
            else
            {
               $msg  = "Failed to add data...";
            }
        }
        else
        {
            $msg  = "Failed to add data...";
        }
        $app->redirect($link,$msg);
    }
    public function updateAstro($details)
    {
       //print_r($data);exit;
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $id             = $details['id'];$img_name      = $details['img_name'];
        $img_id         = $uniq_name;    $addr1         = $details['addr1'];
        $addr2          = $details['addr2'];$city       = $details['city'];
        $state          = $details['state'];$country    = $details['country'];
        $pcode          = $details['pcode'];$phone      = $details['phone'];
        $mobile         = $details['mobile'];$whatsapp  = $details['whatsapp'];
        $website        = $details['website'];$info     = $details['info'];$status  = 'visible';
        $fields         = array(
                                $db->quoteName('addr_1').'='.$db->quote($addr1),
                                $db->quoteName('addr_2').'='.$db->quote($addr2),
                                $db->quoteName('city').'='.$db->quote($city),
                                $db->quoteName('state').'='.$db->quote($state),
                                $db->quoteName('country').'='.$db->quote($country),
                                $db->quoteName('postcode').'='.$db->quote($pcode),
                                $db->quoteName('phone').'='.$db->quote($phone),
                                $db->quoteName('mobile').'='.$db->quote($mobile),
                                $db->quoteName('whatsapp').'='.$db->quote($whatsapp),
                                $db->quoteName('website').'='.$db->quote($website),
                                $db->quoteName('info').'='.$db->quote($info),
                                );
        $conditions = array(
                                $db->quoteName('UserId') . ' = '.$db->quote($id)
                            );
        $query->update($db->quoteName('#__user_astrologer'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();
        $app            = JFactory::getApplication();
        $link           = JURI::base().'dashboard?profile_update=success';
        $msg            = "Profile Updated Successfully... ";
        $app            ->redirect($link, $msg);
       
    }
}
?>
