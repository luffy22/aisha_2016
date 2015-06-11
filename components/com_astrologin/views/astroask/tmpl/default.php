<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>
<div id="ques_page_1">
<h3>Enter Your Details</h3>
<div class="spacer"></div>
<form class="form-horizontal" id="ques_form" role="form" enctype="application/x-www-form-urlencoded" method="post" 
      action="<?php echo JRoute::_('index.php?option=com_astrologin&task=askQuestions1'); ?>">
<div class="form-group" id="ques_grp_1">
    <label for="ques_1" class="col-sm-2 control-label">Name:</label>
    <div class="col-sm-10">
    <input type="text" name="ques_name" class="form-control" id="ques_1" placeholder="Enter your full name" />
    <span class="form-control-feedback" id="ques_ico_1"></span>
    <span class="error1" id="ques_err_1">Please input a valid name.</span>
    </div>
</div>
    <div class="form-group" id="ques_grp_2">
    <label for="ques_2" class="col-sm-2 control-label">Email:</label>
    <div class="col-sm-10">
    <input type="email" name="quest_email" class="form-control" id="ques_2" placeholder="Enter your email" />
    <span class="form-control-feedback" id="ques_ico_2"></span>
    <span class="error1" id="ques_err_2">Please input a valid email.</span>
    </div>
</div>
<div class="form-group">
    <label for="ques_gender" class="col-sm-2 control-label">Gender:</label>
    <div class="col-sm-10">
    <input type="radio" name="ques_gender" value="male" id="ques_gender1"> Male
    <input type="radio" name="ques_gender" value="female" id="ques_gender2"> Female
    </div>
</div>
<div class="form-group" id="ques_grp_3">
    <label for="dob" class="col-sm-2 control-label">Date Of Birth:</label>
    <div class="col-sm-10">
    <input type="text" name="dob" id="ques_dob" class="form-control" placeholder="Date Of Birth in Year/Month/Day Format" />
    <span class="form-control-feedback" id="ques_ico_3"></span>
    <span class="error1" id="ques_err_3">Please input a valid date of birth.</span>
    </div>
</div>
<div class="form-group">
    <label for="dob" class="col-sm-2 control-label">Time Of Birth:</label>
    <div class="col-sm-10">
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
</div>
<div class="form-group" id="ques_grp_4">
    <label for="dob" class="col-sm-2 control-label">Place Of Birth</label>
    <div class="col-sm-10 ui-widget">
    <input type="text" id="ques_pob" name="ques_pob" class="form-control" placeholder="Enter full name of city/town, state, country" />
    <span class="form-control-feedback" id="ques_ico_4"></span>
    <span class="error1" id="ques_err_4">Please enter city/town name, country name</span>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-10">
        <button type="button" class="btn btn-primary" name="quesnext" onclick="javascript:checkDetails();return false;">Next</button>
        <button type="button" class="btn btn-danger">Reset</button>
    </div>
</div>
</div>
<div id="ques_page_2">
    <h3>Ask Your Questions (max 3 questions)</h3>
    <div class="form-group" id="ques_grp_5">
        <label>Number of Questions</label>
        <div class="col-sm-10">
            <select>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
        </div>
    </div>
</div>
</form>



</body>
