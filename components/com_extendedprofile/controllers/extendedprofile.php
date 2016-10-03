<?php
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/controller.php';
class ExtendedProfileControllerExtendedProfile extends ExtendedProfileController
{
    public function registerAstro()
    {
        if(isset($_POST['submit_profile']))
        {
            $membership     = $_POST['astro_type'];
            
            if(!isset($_POST['condition_profile']))
            {
                header('Location: '.JURi::base().'dashboard?terms=no');
            }
            else
            {
                if($membership == "free")
                {
                    $user_details   = array(
                                            'membership'=>$membership
                                    );
                    $model          = $this->getModel('extendedprofile');  // Add the array to model
                    $data           = $model->saveUser($user_details);
                }
                else if($membership == "paid")
                {
                    $pay_type       = $_POST['astro_pay'];
                    echo $pay_type;exit;
                    $user_details = array('memership'=>$membership,);
                   
                }
                
            }
            
        }
        
    }
    public function updateUser()
    {
        if(isset($_POST['update_profile']))
        {
            if($_POST['astro_confirm']=="no")
            {
                $gender     = $_POST['gender_profile']; $dob    = $_POST['dob_profile']; $id = $_POST['profile_id'];
                $tob        = strtotime($_POST['tob_profile_hr'].":".$_POST['tob_profile_min'].":".$_POST['tob_profile_sec']);
                $pob        = $_POST['pob_profile'];$astro = "no";
                $user_details   = array(
                                        'gender'=>$gender,'dob'=>$dob,'pob'=>$pob,
                                    'tob'=>$tob,'astro'=>$astro, 'userid'=>$id
                                    );
                $model          = $this->getModel('extendedprofile');  // Add the array to model
                $data           = $model->updateUser($user_details);
            }
            else if($_POST['astro_confirm']=="yes")
            {
                if(!isset($_POST['condition_profile']))
                {
                    header('Location: '.JURi::base().'preference?terms=no');
                }
                else
                {
                    echo "He/She is an Astrologer";
                }
            }
       }
       else if(isset($_POST['save_profile']))
       {
           echo "calls";exit;
       }
    }
    public function saveAstro()
    {
        $id         = $_POST['profile_id'];
        $img        = $_FILES['astro_img']['name'];$img_type    = $_FILES['astro_img']['type'];
        $tmp        = $_FILES['astro_img']['tmp_name'];
        $img_size   = round((filesize($_FILES['astro_img']['tmp_name'])/1024),2);
        $addr1        = $_POST['astro_addr1'];
        $addr2      = $_POST['astro_addr2'];$city       = $_POST['astro_city'];
        $state      = $_POST['astro_state'];$country    = $_POST['astro_country'];
        $pcode      = $_POST['astro_pcode'];$phone      = $_POST['astro_code'].'-'.$_POST['astro_phone'];
        $mobile     = $_POST['astro_mobile'];
        if(!empty($_POST['astro_whatsapp'])){$whatsapp="yes";}else{$whatsapp="no";};
        $website   = $_POST['astro_web'];$info          = $_POST['astro_info'];
        
        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $detectedType = exif_imagetype($_FILES['astro_img']['tmp_name']);
        $error = !in_array($detectedType, $allowedTypes);
         
        if($error)
        {
            $url = JURi::base().'preference?image=false';
            $this->directUrl($url);
        }
        else if($img_size >= 2048)
        {
            $url = JRi::base().'preference?image=size';
            $this->directUrl($url);
        }
        else
        {
               //echo $tmp;exit;
            $user_details   = array(
                                        'id'=>$id,'img_name'=>$img,'tmp_name'=>$tmp,
                                    'addr1'=>$addr1,'addr2'=>$addr2, 'city'=>$city,
                                    'state'=>$state,'country'=>$country,'pcode'=>$pcode,
                                    'phone'=>$phone,'mobile'=>$mobile,'whatsapp'=>$whatsapp,
                                    'website'=>$website,'info'=>$info
                                    );
            $model          = $this->getModel('extendedprofile');  // Add the array to model
            $data           = $model->saveAstro($user_details);
        }
    }
    public function updateAstro()
    {
        $id         = $_POST['profile_id'];
        //$img        = $_FILES['astro_img']['name'];$img_type    = $_FILES['astro_img']['type'];
        //$tmp        = $_FILES['astro_img']['tmp_name'];
        //$img_size   = round((filesize($_FILES['astro_img']['tmp_name'])/1024),2);
        $addr1        = $_POST['astro_addr1'];
        $addr2      = $_POST['astro_addr2'];$city       = $_POST['astro_city'];
        $state      = $_POST['astro_state'];$country    = $_POST['astro_country'];
        $pcode      = $_POST['astro_pcode'];$phone      = $_POST['astro_code'].'-'.$_POST['astro_phone'];
        $mobile     = $_POST['astro_mobile'];
        if(!empty($_POST['astro_whatsapp'])){$whatsapp="yes";}else{$whatsapp="no";};
        $website   = $_POST['astro_web'];$info          = $_POST['astro_info'];
        
        /*$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $detectedType = exif_imagetype($_FILES['astro_img']['tmp_name']);
        $error = !in_array($detectedType, $allowedTypes);
               
        if($error)
        {
            $url = JURi::base().'preference?image=false';
            $this->directUrl($url);
        }
        else if($img_size >= 2048)
        {
            $url = JRi::base().'preference?image=size';
            $this->directUrl($url);
        }
        else
        {*/
               //echo $tmp;exit;
            $user_details   = array(
                                        'id'=>$id,
                                    'addr1'=>$addr1,'addr2'=>$addr2, 'city'=>$city,
                                    'state'=>$state,'country'=>$country,'pcode'=>$pcode,
                                    'phone'=>$phone,'mobile'=>$mobile,'whatsapp'=>$whatsapp,
                                    'website'=>$website,'info'=>$info
                                    );
            $model          = $this->getModel('extendedprofile');  // Add the array to model
            $data           = $model->updateAstro($user_details);
       // }
        
    }
    protected function directUrl($url)
    {
        header('Location: '.$url);
    }
}
