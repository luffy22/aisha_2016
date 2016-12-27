<?php
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/controller.php';
class AstrologinControllerAstroask extends AstroLoginController
{
    public function askQuestions()
    { 

            $name           = $_POST['ques_name'];
            $email          = $_POST['ques_email'];
            $gender         = $_POST['ques_gender'];
            $dob            = $_POST['ques_dob'];
            $pob            = $_POST['ques_pob'];
            $tob            = $_POST['lagna_hr'].":".$_POST['lagna_min'].":".$_POST['lagna_sec']." ".$_POST['lagna_time'];
            $fees           = $_POST['ques_charge'];
            $user_loc       = $_POST['user_loc'];
            $user_curr      = $_POST['user_curr'];
            $user_curr_full = $_POST['user_curr_full'];
            $choice         = $_POST['ques_choice'];
            if(isset($_POST['pay_type']))
            {
                $pay_type   = $_POST['pay_type'];
            }
            else
            {
                $pay_type   = "paypal";
            }
            //echo $pay_type;exit;
            $explain        = $_POST['ques_explain'];
            if($explain == "short"&&$choice=="1")
            {
                $option1    = $_POST['ques_1_option'];
                $ques1      = $_POST['ques_ask_1'];
                $ques_det1  = "0";
                $option2    = "0";
                $ques2      = "0";
                $ques_det2  = "0";
                $option3    = "0";
                $ques3      = "0";
                $ques_det3  = "0";         
            }
            else if($explain=="short"&&$choice=="2")
            {
                $option1    = $_POST['ques_1_option'];
                $ques1      = $_POST['ques_ask_1'];
                $ques_det1  = "0";
                $option2    = $_POST['ques_2_option'];
                $ques2      = $_POST['ques_ask_2'];
                $ques_det2  = "0";
                $option3    = "0";
                $ques3      = "0";
                $ques_det3  = "0";
            }
            else if($explain=="short"&&$choice=="3")
            {
                $option1    = $_POST['ques_1_option'];
                $ques1      = $_POST['ques_ask_1'];
                $ques_det1  = "0";
                $option2    = $_POST['ques_2_option'];
                $ques2      = $_POST['ques_ask_2'];
                $ques_det2  = "0";
                $option3    = $_POST['ques_3_option'];
                $ques3      = $_POST['ques_ask_3'];
                $ques_det3  = "0";
            }
            else if($explain=="detail"&&$choice=="1")
            {
                $option1    = $_POST['ques_1_option'];
                $ques1      = $_POST['ques_ask_1'];
                $ques_det1  = $_POST['ques_detail_1'];
                $option2    = "0";
                $ques2      = "0";
                $ques_det2  = "0";
                $option3    = "0";
                $ques3      = "0";
                $ques_det3  = "0";
            }
            else if($explain=="detail"&&$choice=="2")
            {
                $option1    = $_POST['ques_1_option'];
                $ques1      = $_POST['ques_ask_1'];
                $ques_det1  = $_POST['ques_detail_1'];
                $option2    = $_POST['ques_2_option'];
                $ques2      = $_POST['ques_ask_2'];
                $ques_det2  = $_POST['ques_detail_2'];
                $option3    = "0";
                $ques3      = "0";
                $ques_det3  = "0";
            }
            else if($explain=="detail"&&$choice=="3")
            {
                $option1    = $_POST['ques_1_option'];
                $ques1      = $_POST['ques_ask_1'];
                $ques_det1  = $_POST['ques_detail_1'];
                $option2    = $_POST['ques_2_option'];
                $ques2      = $_POST['ques_ask_2'];
                $ques_det2  = $_POST['ques_detail_2'];
                $option3    = $_POST['ques_3_option'];
                $ques3      = $_POST['ques_ask_3'];
                $ques_det3  = $_POST['ques_detail_3'];
           }
           $details    = array(
                                "name"=>$name,"email"=>$email,"gender"=>$gender,"explain"=>$explain,
                                "dob"=>$dob,"pob"=>$pob, "tob"=>$tob, "choice"=>$choice, "pay_type"=>$pay_type,
                                "fees"=>$fees,"user_loc"=>$user_loc,"user_curr"=>$user_curr,"user_curr_full"=>$user_curr_full,
                                "opt1"=>$option1,"ques1"=>$ques1,"ques_det1"=>$ques_det1,
                                "opt2"=>$option2,"ques2"=>$ques2,"ques_det2"=>$ques_det2,
                                "opt3"=>$option3,"ques3"=>$ques3,"ques_det3"=>$ques_det3,
                                );
           //print_r($details);exit;
            $model          = $this->getModel('astroask');  // Add the array to model
            $model->askQuestions($details);
        
    }
    public function confirmPayment()
    {
        $id             = $_GET['id'];
        $auth_id       = $_GET['auth_id'];
        $token          = $_GET['token'];
        $details        = array("paypal_id"=>$id,"auth_id"=>$auth_id,"token"=>$token);
        $model          = $this->getModel('astroask');  // Add the array to model
        $model          ->authorizePayment($details);
    }
    public function failPayment()
    {
        $token              = $_GET['token'];
        $failid             = $_GET['failid'];
        $details        = array("token"=>$token, "fail_id"=>$failid);
        $model          = $this->getModel('astroask');  // Add the array to model
        $model          ->failPayment($details);
    }
    public function confirmCCPayment()
    {
        $token             = $_GET['token'];
        $track_id          = $_GET['track_id'];
        $bank_ref          = $_GET['bank_ref'];
        $status            = $_GET['status'];
        
        $details        = array("token"=>$token,"trackid"=>$track_id,"bankref"=>$bank_ref,"status"=>$status);
        $model          = $this->getModel('astroask');  // Add the array to model
        $model          ->confirmCCPayment($details);
    }
}
?>
