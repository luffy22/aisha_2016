<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
function getIP() {
  /*foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
     if (array_key_exists($key, $_SERVER) === true) {
        foreach (explode(',', $_SERVER[$key]) as $ip) {
           if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
              return $ip;
           }
        }
     }
  }*/

  //$ip = '212.58.244.20';
  //$ip   = '223.223.146.119';
  //$ip   = '208.91.198.52';

  //$ip    = '176.102.49.192'; // us ip
  $ip = '122.175.21.127';
  return $ip;
}
   
$json = file_get_contents('http://getcitydetails.geobytes.com/GetCityDetails?fqcn='. getIP()); 
$data = json_decode($json);

if(isset($_GET['email'])&&($_GET['email']=='sent'))
{
    echo "success";   
}
else
{
?>

<div class="spacer"></div>
<form class="form-horizontal" id="ques_form" role="form" enctype="application/x-www-form-urlencoded" method="post" 
      action="<?php echo JRoute::_('index.php?option=com_astrologin&task=astroask.askQuestions'); ?>">
<div id="ques_page_1">
<h3>Enter Your Details</h3>
<div class="spacer"></div>

<div class="form-group" id="ques_grp_1">
    <label for="ques_1">Name:</label>
    <input type="text" name="ques_name" class="form-control" id="ques_1" placeholder="Enter your full name" />
    <span class="error1" id="ques_err_1">Please input a valid name.</span>
</div>
<div class="form-group" id="ques_grp_2">
    <label for="ques_2">Email:</label>
    <input type="email" name="ques_email" class="form-control" id="ques_2" placeholder="Enter your email" />
    <span class="error1" id="ques_err_2">Please input a valid email.</span>
</div>
<div class="form-group" id="ques_grp_3">
    <label for="ques_gender">Gender:</label><br/>
    <input type="radio" name="ques_gender" value="male" id="ques_gender1" /> Male
    <input type="radio" name="ques_gender" value="female" id="ques_gender2" checked /> Female
    <span class="error1" id="ques_err_3">Enter a gender.</span>
</div>
<div class="form-group" id="ques_grp_4">
    <label for="dob" >Date Of Birth:</label>
    <input type="text" name="ques_dob" id="ques_dob" class="form-control" placeholder="Date Of Birth in Year/Month/Day Format" />
    <span class="error1" id="ques_err_4">Please input a valid date of birth.</span>
    </div>
<div class="form-group">
    <label>Time Of Birth:</label><br/>
    <select class="select2" id="lagna_tob_hr" name="lagna_hr">
    <?php
         for($i=0;$i<12;$i++)
         {
             if($i<10)
             {
    ?>
            <option><?php echo "0".$i; ?></option>
    <?php
             }
            else
            {
    ?>
            <option><?php echo $i; ?></option>
    <?php
            }
         }
    ?>
    </select>
    <select class="select2" id="lagna_tob_min" name="lagna_min">
    <?php
        for($i=0;$i<60;$i++)
        {
           if($i<10)
           {
            ?>
            <option><?php echo "0".$i; ?></option>
            <?php    
           }
           else
            {
    ?>
            <option><?php echo $i; ?></option>
    <?php
            }
        }
    ?>
    </select>
    <select class="select2" id="lagna_tob_sec" name="lagna_sec">
    <?php
        for($i=0;$i<60;$i++)
        {
           if($i<10)
           {
            ?>
            <option><?php echo "0".$i; ?></option>
            <?php    
           }
           else
            {
    ?>
            <option><?php echo $i; ?></option>
    <?php
            }
        }
    ?>
    </select>
    <select class="select2" id="lagna_tob_am-pm" name="lagna_time">
        <option>AM</option>
        <option>PM</option>
    </select>
</div>
<div class="form-group" id="ques_grp_5">
    <label for="ques_pob">Place Of Birth</label>
    <input type="text" id="ques_pob" name="ques_pob" class="form-control" value="<?php echo $data->geobytesfqcn ?>" placeholder="Enter full name of city/town, state, country" />
    <span class="error1" id="ques_err_5">Please enter city/town name, country name</span>
</div>
<div class="form-group">
        <button type="button" class="btn btn-primary" name="quesnext" onclick="javascript:checkDetails();return false;">Next</button>
        <button type="reset" class="btn btn-danger">Reset</button>
</div>
</div>
<div id="ques_page_2">
<input type="hidden" name="user_loc" id="user_loc" value="<?php echo $data->geobytesinternet; ?>" />
<input type="hidden" name="user_cont" id="user_cont" value="<?php echo $data->geobytesmapreference; ?>" />
<input type="hidden" name="user_curr" id="user_curr" value="<?php echo $data->geobytescurrencycode; ?>" />
<input type="hidden" name="user_curr_full" id="user_curr_full" value="<?php echo $data->geobytescurrency; ?>" />
<h3>Ask Your Questions (max 3 questions)</h3>
<input type="hidden" name="ques_charge" id="ques_charge"   />
<?php
    if($data->geobytesinternet == 'IN')
    {
?>
     <div class="form-group" id="ques_grp_6">
        <label for="ques_choice">Do you want short or detailed explanation?</label>
            <select class="form-control" id="ques_explain" name="ques_explain" onchange="javascript:explainChoice()">
                <option value="none">Please enter your choice</option>
                <option value="detail">Detailed Explanation (300 &#8377; for each answer)</option>
                <option value="short">Short Explanation (100 &#8377; for each answer)</option>
            </select>
    </div>
<?php
    }
    else if($data->geobytesinternet == 'LK'||$data->geobytesinternet == 'PK'
            ||$data->geobytesinternet == 'BD'||$data->geobytesinternet == 'NP'
            ||$data->geobytesinternet == 'ID')
    {
    ?>
    <div class="form-group" id="ques_grp_6">
        <label for="ques_choice">Do you want short or detailed explanation?</label>
            <select class="form-control" id="ques_explain" name="ques_explain" onchange="javascript:explainChoice()">
                <option value="none">Please enter your choice</option>
                <option value="detail">Detailed Explanation (5 &#8356; for each answer)</option>
                <option value="short">Short Explanation (1.5 &#8356; for each answer)</option>
            </select>
    </div>
<?php
    }
    else if($data->geobytesinternet=='UK')
    {
?>
    <div class="form-group" id="ques_grp_6">
    <label for="ques_choice">Do you want short or detailed explanation?</label>
    <select class="form-control" id="ques_explain" name="ques_explain" onchange="javascript:explainChoice()">
        <option value="none">Please enter your choice</option>
        <option value="detail">Detailed Explanation (10 &#8356; for each answer)</option>
        <option value="short">Short Explanation (3.5 &#8356; for each answer)</option>
    </select>
    </div>
<?php
    }
    else if(($data->geobytesinternet!=='UK')&&($data->geobytesmapreference=="Europe"))
    {
    ?>
    <div class="form-group" id="ques_grp_6">
    <label for="ques_choice">Do you want short or detailed explanation?</label>
    <select class="form-control" id="ques_explain" name="ques_explain" onchange="javascript:explainChoice()">
        <option value="none">Please enter your choice</option>
        <option value="detail">Detailed Explanation (10 &#8364; for each answer)</option>
        <option value="short">Short Explanation (3.5 &#8364; for each answer)</option>
    </select>
    </div>
<?php
    }
    else if($data->geobytesinternet=='CA')
    {
?>
    <div class="form-group" id="ques_grp_6">
    <label for="ques_choice">Do you want short or detailed explanation?</label>
    <select class="form-control" id="ques_explain" name="ques_explain" onchange="javascript:explainChoice()">
        <option value="none">Please enter your choice</option>
        <option value="detail">Detailed Explanation (15 &#36; for each answer)</option>
        <option value="short">Short Explanation (5 &#36; for each answer)</option>
    </select>
    </div>
<?php
    }
     else if($data->geobytesinternet=='AU')
    {
?>
    <div class="form-group" id="ques_grp_6">
    <label for="ques_choice">Do you want short or detailed explanation?</label>
    <select class="form-control" id="ques_explain" name="ques_explain" onchange="javascript:explainChoice()">
        <option value="none">Please enter your choice</option>
        <option value="detail">Detailed Explanation (15 &#36; for each answer)</option>
        <option value="short">Short Explanation (5 &#36; for each answer)</option>
    </select>
    </div>
<?php
    } 
     else if($data->geobytesinternet=='SG')
    {
?>
    <div class="form-group" id="ques_grp_6">
    <label for="ques_choice">Do you want short or detailed explanation?</label>
    <select class="form-control" id="ques_explain" name="ques_explain" onchange="javascript:explainChoice()">
        <option value="none">Please enter your choice</option>
        <option value="detail">Detailed Explanation (15 &#36; for each answer)</option>
        <option value="short">Short Explanation (5 &#36; for each answer)</option>
    </select>
    </div>
<?php
    } 
    else if($data->geobytesinternet=='NZ')
    {
?>
    <div class="form-group" id="ques_grp_6">
    <label for="ques_choice">Do you want short or detailed explanation?</label>
    <select class="form-control" id="ques_explain" name="ques_explain" onchange="javascript:explainChoice()">
        <option value="none">Please enter your choice</option>
        <option value="detail">Detailed Explanation (15 &#36; for each answer)</option>
        <option value="short">Short Explanation (5 &#36; for each answer)</option>
    </select>
    </div>
<?php
    } 
    else if($data->geobytesinternet=='US')
    {
?>
    <div class="form-group" id="ques_grp_6">
    <label for="ques_choice">Do you want short or detailed explanation?</label>
    <select class="form-control" id="ques_explain" name="ques_explain" onchange="javascript:explainChoice()">
        <option value="none">Please enter your choice</option>
        <option value="detail">Detailed Explanation (15 &#36; for each answer)</option>
        <option value="short">Short Explanation (5 &#36; for each answer)</option>
    </select>
    </div>
<?php
    } 
    else
    {
?>
    <div class="form-group" id="ques_grp_6">
    <label for="ques_choice">Do you want short or detailed explanation?</label>
    <select class="form-control" id="ques_explain" name="ques_explain" onchange="javascript:explainChoice()">
        <option value="none">Please enter your choice</option>
        <option value="detail">Detailed Explanation (10 &#36; for each answer)</option>
        <option value="short">Short Explanation (3.5 &#36; for each answer)</option>
    </select>
    </div>
<?php
    }
?>    <div class="form-group" id="ques_grp_5">
        <label for="ques_choice">Number of Questions</label>
            <select class="form-control" id="ques_choice" name="ques_choice" onchange="javascript:explainChoice()">
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
    </div>
    <div class="form-group" id="ques_grp_7">
        
    </div>
    <div class="form-group">
        <div class="col-sm-10">
            <button type="button" class="btn btn-primary" name="quesnext1" onclick="javascript:backPage();return false;">Back</button>
            <button type="button" class="btn btn-primary" name="quesnext2" onclick="javascript:nextPage();return false;">Next</button>
            
        </div>
    </div>
</div>
<div id="ques_page_3">
    <div id="ques-content">
        <div id="loadergif2" class="loader"><img src="<?php echo $this->baseurl ?>/images/loader.gif" /></div>
    </div>

</div>
</form>
</div>
<?php
}
?>
