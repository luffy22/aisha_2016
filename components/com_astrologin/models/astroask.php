<?php

defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
class AstrologinModelAstroask extends JModelItem
{
    public function askQuestions($details)
    {
        $token      = uniqid('token_');
        $name       = $details['name'];
        $email      = $details['email'];
        $gender     = $details['gender'];
        $explain    = $details['explain'];
        $dob        = $details['dob'];
        $tob        = $details['tob'];
        $pob        = $details['pob'];
        $choice     = $details['choice'];
        $opt1       = $details['opt1'];
        $ques1      = $details['ques1'];
        $ques_det1  = $details['ques_det1'];
        $opt2       = $details['opt2'];
        $ques2      = $details['ques2'];
        $ques_det2  = $details['ques_det2'];
        $opt3       = $details['opt3'];
        $ques3      = $details['ques3'];
        $ques_det3  = $details['ques_det3'];
        
        
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);

        $columns        = array('UniqueID','name','email','gender', 'dob', 'tob', 'pob','choice','explain_choice',
                                'ques_topic1','ques_1','ques_1_explain',
                                'ques_topic2','ques_2','ques_2_explain',
                                'ques_topic3','ques_3','ques_3_explain'
                                );
        $values         = array(
                                $db->quote($token), $db->quote($name), $db->quote($email),
                                $db->quote($gender), $db->quote($dob), $db->quote($tob),
                                $db->quote($pob), $db->quote($choice),$db->quote($explain), 
                                $db->quote($opt1), $db->quote($ques1), $db->quote($ques_det1),
                                $db->quote($opt2), $db->quote($ques2), $db->quote($ques_det2),
                                $db->quote($opt3), $db->quote($ques3), $db->quote($ques_det3),
                                );
        // Prepare the insert query
        $query    ->insert($db->quoteName('#__questions'))
                        ->columns($db->quoteName($columns))
                        ->values(implode(',', $values));
        // Set the query using our newly populated query object and execute it
        $db             ->setQuery($query);
        $result          = $db->query();
        // sending notification email via function
        if($result)
        {
            $query              ->clear();
            $query              ->select($db->quoteName(array('UniqueID','name','email',
                                        'gender','dob','pob','tob', 'choice', 'explain_choice',
                                        'ques_topic1','ques_1','ques_1_explain',
                                        'ques_topic2','ques_2','ques_2_explain',
                                        'ques_topic3','ques_3','ques_3_explain')))
                                ->from($db->quoteName('#__questions'))
                                ->where($db->quoteName('UniqueID').'='.$db->quote($token));
           $db                  ->setQuery($query);
           $row                 = $db->loadAssoc();
           $details             = array(
                                            'token'=>$row['UniqueID'],'name'=>$row['name'],'email'=>$row['email'],
                                            'gender'=>$row['gender'],'dob'=>$row['dob'],'tob'=>$row['tob'],
                                            'pob'=>$row['pob'],'choice'=>$row['choice'],'explain'=>$row['explain_choice'],
                                            'ques_topic_1'=>$row['ques_topic1'],'ques_1'=>$row['ques_1'],'ques_1_explain'=>$row['ques_1_explain'],
                                            'ques_topic_2'=>$row['ques_topic2'],'ques_2'=>$row['ques_2'],'ques_2_explain'=>$row['ques_2_explain'],
                                            'ques_topic_3'=>$row['ques_topic3'],'ques_3'=>$row['ques_3'],'ques_3_explain'=>$row['ques_3_explain']
                                        );
           $this->sendConfirmMail($details);

        }
        else
        {
            $app        ->redirect('index.php?option=com_astrologin&view=astroask&failure=fail'); 
        }
    }
    protected function sendConfirmMail($details)
    {
        $name               = $details['name'];
        $gender             = $details['gender'];
        $dob                = $details['dob'];
        $pob                = $details['pob'];
        $tob                = $details['tob'];
        $choice             = $details['choice'];
        $explain            = $details['explain'];
        $to                 = $details['email'];
        $bcc                = 'kopnite@gmail';
        $subject            = "Ask Astrologer Token No: ".$details['token'];
        if($explain=="detail"&&$choice=='1')
        {
            $body               = "Dear ".$name.",<br/>"."<html>&nbsp;&nbsp;&nbsp;</html>This is to confirm that your question form has been received. Once your payment of 300 <html>&#8377;</html> is received we would process your query and give answer and logical solution to your asked questions in 7 Working Days. 
                                Once you transfer the money please reply back to this email with confirmation of payment. If payment in not received in 10 Working Days then there would be termed as Cancellation of Order.<br/><br/>";
            $body               .= "Your Details are as below. Please reply back if any changes are needed.<br/>";
            $body               .= "Name: ".$name."<br/>";
            $body               .= "Email: ".$to."<br/>";
            $body               .= "Gender: ".$gender."<br/>";
            $body               .= "Date Of Birth: ".$dob."<br/>";
            $body               .= "Time Of Birth: ".$tob."<br/>";
            $body               .= "Place Of Birth: ".$pob."<br/>";
            $body               .= "Number Of Questions: ".$choice."<br/>";
            $body               .= "Explanation (Detail/Short): ".$explain."<br/>";
            $body               .= "Question 1:<br/>";
            $body               .= "Topic: ".$details['ques_topic_1']."<br/>";
            $body               .= "Question: ".$details['ques_1']."<br/>";
            $body               .= "Background: ".$details['ques_1_explain']."<br/>";
            $body               .= "<br/><div style='align:right'>Your Sincerely,<br/>Admin(Rohan Desai)</div>";

        }
        else if($explain=="short"&&$choice=='1')
        {
            $body               = "Dear ".$name."<br/>"."This is to confirm that your question form has been received. Once your payment of 100 <html>&#8377;</html> is received we would provide an answer to your question in 7 Working Days. 
                                Once you transfer the money please reply back to this email with confirmation of payment. If payment in not received in 10 Working Days then there would be termed as Cancellation of Order.<br/><br/>";
            $body               .= "Your Details are as below. Please reply back if any changes are needed.<br/>";
            $body               .= "Name: ".$name."<br/>";
            $body               .= "Email: ".$to."<br/>";
            $body               .= "Gender: ".$gender."<br/>";
            $body               .= "Date Of Birth: ".$dob."<br/>";
            $body               .= "Time Of Birth: ".$tob."<br/>";
            $body               .= "Place Of Birth: ".$pob."<br/>";
            $body               .= "Number Of Questions: ".$choice."<br/>";
            $body               .= "Explanation (Detail/Short): ".$explain."<br/>";
            $body               .= "Question 1:<br/>";
            $body               .= "Topic: ".$details['ques_topic_1']."<br/>";
            $body               .= "Question: ".$details['ques_1']."<br/>";
            $body               .= "<br/><div style='align:right'>Your Sincerely,<br/>Admin(Rohan Desai)</div>";
        }
        else if($explain=="detail"&&$choice=='2')
        {
            $body               = "Dear ".$name."<br/>"."This is to confirm that your question form has been received. Once your payment of 300 <html>&#8377;</html> is received we would process your query and give answer and logical solution to your asked questions in 7 Working Days. 
                                Once you transfer the money please reply back to this email with confirmation of payment. If payment in not received in 10 Working Days then there would be termed as Cancellation of Order.<br/><br/>";
            $body               .= "Your Details are as below. Please reply back if any changes are needed.<br/>";
            $body               .= "Name: ".$name."<br/>";
            $body               .= "Email: ".$email."<br/>";
            $body               .= "Gender: ".$gender."<br/>";
            $body               .= "Date Of Birth: ".$dob."<br/>";
            $body               .= "Time Of Birth: ".$tob."<br/>";
            $body               .= "Place Of Birth: ".$pob."<br/>";
            $body               .= "Number Of Questions: ".$choice."<br/>";
            $body               .= "Explanation (Detail/Short): ".$explain."<br/>";
            $body               .= "Question 1:<br/>";
            $body               .= "Topic: ".$details['ques_topic_1']."<br/>";
            $body               .= "Question: ".$details['ques_1']."<br/>";
            $body               .= "Background: ".$details['ques_1_explain']."<br/>";
            $body               .= "Question 2:<br/>";
            $body               .= "Topic: ".$details['ques_topic_2']."<br/>";
            $body               .= "Question: ".$details['ques_2']."<br/>";
            $body               .= "Background: ".$details['ques_2_explain']."<br/>";
            $body               .= "<br/><div style='align:right'>Your Sincerely,<br/>Admin(Rohan Desai)</div>";
        }
         else if($explain=="short"&&$choice=='2')
        {
            $body               = "Dear ".$name."<br/>"."This is to confirm that your question form has been received. Once your payment of 100 <html>&#8377;</html> is received we would provide an answer to your question in 7 Working Days. 
                                Once you transfer the money please reply back to this email with confirmation of payment. If payment in not received in 10 Working Days then there would be termed as Cancellation of Order.<br/><br/>";
            $body               .= "Your Details are as below. Please reply back if any changes are needed.<br/>";
            $body               .= "Name: ".$name."<br/>";
            $body               .= "Email: ".$email."<br/>";
            $body               .= "Gender: ".$gender."<br/>";
            $body               .= "Date Of Birth: ".$dob."<br/>";
            $body               .= "Time Of Birth: ".$tob."<br/>";
            $body               .= "Place Of Birth: ".$pob."<br/>";
            $body               .= "Number Of Questions: ".$choice."<br/>";
            $body               .= "Explanation (Detail/Short): ".$explain."<br/>";
            $body               .= "Question 1:<br/>";
            $body               .= "Topic: ".$details['ques_topic_1']."<br/>";
            $body               .= "Explanation (Detail/Short): ".$details->explain."<br/>";
            $body               .= "Question 2:<br/>";
            $body               .= "Topic: ".$details['ques_topic_2']."<br/>";
            $body               .= "Question: ".$details['ques_2']."<br/>";
            $body               .= "<br/><div style='align:right'>Your Sincerely,<br/>Admin(Rohan Desai)</div>";
        }
         else if($explain=="detail"&&$choice=='3')
        {
            $body               = "Dear ".$name."<br/>"."This is to confirm that your question form has been received. Once your payment of 300 <html>&#8377;</html> is received we would process your query and give answer and logical solution to your asked questions in 7 Working Days. 
                                Once you transfer the money please reply back to this email with confirmation of payment. If payment in not received in 10 Working Days then there would be termed as Cancellation of Order.<br/><br/>";
            $body               .= "Your Details are as below. Please reply back if any changes are needed.<br/>";
            $body               .= "Name: ".$name."<br/>";
            $body               .= "Email: ".$email."<br/>";
            $body               .= "Gender: ".$gender."<br/>";
            $body               .= "Date Of Birth: ".$dob."<br/>";
            $body               .= "Time Of Birth: ".$tob."<br/>";
            $body               .= "Place Of Birth: ".$pob."<br/>";
            $body               .= "Number Of Questions: ".$choice."<br/>";
            $body               .= "Explanation (Detail/Short): ".$explain."<br/>";
            $body               .= "Question 1:<br/>";
            $body               .= "Topic: ".$details['ques_topic_1']."<br/>";
            $body               .= "Question: ".$details['ques_1']."<br/>";
            $body               .= "Background: ".$details['ques_1_explain']."<br/>";
            $body               .= "Question 2:<br/>";
            $body               .= "Topic: ".$details['ques_topic_2']."<br/>";
            $body               .= "Question: ".$details['ques_2']."<br/>";
            $body               .= "Background: ".$details['ques_2_explain']."<br/>";
            $body               .= "Question 3:<br/>";
            $body               .= "Topic: ".$details['ques_topic_3']."<br/>";
            $body               .= "Question: ".$details['ques_3']."<br/>";
            $body               .= "Background: ".$details['ques_3_explain']."<br/>";
            $body               .= "<br/><div style='align:right'>Your Sincerely,<br/>Admin(Rohan Desai)</div>";
        }
         else if($explain=="short"&&$choice=='3')
        {
            $body               = "Dear ".$name."<br/>"."This is to confirm that your question form has been received. Once your payment of 100 <html>&#8377;</html> is received we would provide an answer to your question in 7 Working Days. 
                                Once you transfer the money please reply back to this email with confirmation of payment. If payment in not received in 10 Working Days then there would be termed as Cancellation of Order.<br/><br/>";
            $body               .= "Your Details are as below. Please reply back if any changes are needed.<br/>";
            $body               .= "Name: ".$name."<br/>";
            $body               .= "Email: ".$email."<br/>";
            $body               .= "Gender: ".$gender."<br/>";
            $body               .= "Date Of Birth: ".$dob."<br/>";
            $body               .= "Time Of Birth: ".$tob."<br/>";
            $body               .= "Place Of Birth: ".$pob."<br/>";
            $body               .= "Number Of Questions: ".$choice."<br/>";
            $body               .= "Explanation (Detail/Short): ".$explain."<br/>";
            $body               .= "Question 1:<br/>";
            $body               .= "Topic: ".$details['ques_topic_1']."<br/>";
            $body               .= "Explanation (Detail/Short): ".$details->explain."<br/>";
            $body               .= "Question 2:<br/>";
            $body               .= "Topic: ".$details['ques_topic_2']."<br/>";
            $body               .= "Question: ".$details['ques_2']."<br/>";
            $body               .= "Question 3:<br/>";
            $body               .= "Topic: ".$details['ques_topic_3']."<br/>";
            $body               .= "Question: ".$details['ques_3']."<br/><br/>";
            $body               .= "<br/><div style='align:right'>Your Sincerely,<br/>Admin(Rohan Desai)</div>";
        }
        $mailer             = JFactory::getMailer();
        $config             = JFactory::getConfig();
        $sender             = array( 
                                        $config->get( 'mailfrom' ),
                                        $config->get( 'fromname' ) 
                                    );
 
        $mailer             ->setSender($sender);
        $mailer             ->addRecipient($to);
        $mailer             ->addBCC($bcc, 'Rohan Desai');
        $mailer             ->setSubject($subject);
        $mailer             ->isHTML(true);
        $mailer             ->Encoding = 'base64';
        $mailer             ->setBody($body);
        
        $send = $mailer->Send();
        if ( $send !== true ) {
            echo 'Error sending email: ' . $send->__toString();
        } else {
            $app                =&JFactory::getApplication();
            $app                ->redirect('index.php?option=com_astrologin&view=astrosask&email=sent'); 
        }
    }
}
?>
