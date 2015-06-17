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
    <input type="text" name="dob" id="ques_dob" class="form-control" placeholder="Date Of Birth in Year/Month/Day Format" />
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
    <input type="text" id="ques_pob" name="ques_pob" class="form-control" placeholder="Enter full name of city/town, state, country" />
    <span class="error1" id="ques_err_5">Please enter city/town name, country name</span>
</div>
<div class="form-group">
        <button type="button" class="btn btn-primary" name="quesnext" onclick="javascript:checkDetails();return false;">Next</button>
        <button type="button" class="btn btn-danger">Reset</button>
</div>
</div>
<div id="ques_page_2">
    <h3>Ask Your Questions (max 3 questions)</h3>
    <div class="form-group" id="ques_grp_5">
        <label for="ques_choice">Number of Questions</label>
            <select class="form-control" id="ques_choice" onchange="javascript:explainChoice()">
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
    </div>
    <div class="form-group" id="ques_grp_6">
        <label for="ques_choice">Do you want short or detailed explanation?</label>
            <select class="form-control" id="ques_explain" onchange="javascript:explainChoice()">
                <option value="none">Please enter your choice</option>
                <option value="detail">Detailed Explanation (300 &#8377; for each answer)</option>
                <option value="short">Short Explanation (100 &#8377; for each answer)</option>
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
    <div class="form-group">
        <div class="col-sm-10">
            <button type="button" class="btn btn-primary" name="quesnext3" onclick="javascript:backPage1();return false;">Back</button>
            <button type="button" class="btn btn-primary" name="quesnext4" onclick="javascript:nextPage1();return false;">Next</button>
            
        </div>
    </div>
</div>
</form>



</body>
