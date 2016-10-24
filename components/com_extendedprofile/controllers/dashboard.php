<?php
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/controller.php';
class ExtendedProfileControllerDashboard extends ExtendedProfileController
{
    public function confirmPayment()
    {
        $status         = $_GET['status'];
        $token          = $_GET['token'];
        $uid            = $_GET['uid'];
        if(isset($_GET['sale_id']))
        {
            $sale_id    = $_GET['sale_id'];
        }
        $email          = $_GET['email'];
        if($status == "success")
        {
            $details        = array("status"=>$status, "token"=>$token,"pay_id"=>$sale_id,"email"=>$email,"uid"=>$uid);
        }
         else 
        {
            $details        = array("status"=>$status, "token"=>$token,"email"=>$email,"uid"=>$uid);
        }
        $model          = $this->getModel('dashboard');  // Add the array to model
        $model          ->authorizePayment($details);
    }
    public function confirmCCPayment()
    {
        $status             = $_GET['status'];
        $token              = $_GET['token'];
        $email              = $_GET['email'];
        $track_id           = $_GET['track_id'];
        
        if($status      == "Failure"||$status =="Aborted")
        {
            $details        = array('status'=>$status,'token'=>$token,'email'=>$email,'track_id'=>$track_id);
        }
        else if($status  == "Success")
        {
            $bank_ref       = $_GET['bank_ref'];
            $details        = array('status'=>$status,'token'=>$token,'email'=>$email,'track_id'=>$track_id,
                                    'bank_ref'=>$bank_ref);
        }
        //print_r($details);exit;
        $model          = $this->getModel('dashboard');  // Add the array to model
        $model              ->authorizeCCPayment($details);
    }
}