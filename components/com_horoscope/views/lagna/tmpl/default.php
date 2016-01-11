<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();
		
?>
 <div>
<h2>Calculate Lagna</h2>
<div class="spacer"></div>
<form class="form-horizontal" role="form" enctype="application/x-www-form-urlencoded" method="post" 
      action="<?php echo JRoute::_('index.php?option=com_horoscope&task=lagna.findlagna'); ?>">
    <div class="form-group" id="lagna_grp_1">
        <label for="inputName" class="col-sm-2 control-label">Name:</label>
        <div class="col-sm-10">
        <input type="text" name="fname" class="form-control" id="lagna_1" placeholder="Enter your name..." />
        <span class="form-control-feedback" id="lagna_ico_1"></span>
        <span class="error1" id="lagna_err_1">Please input a valid name.</span>
        </div>
    </div>
    <div class="form-group">
        <label for="inputGender" class="col-sm-2 control-label">Gender:</label>
        <div class="col-sm-10">
        <input type="radio" name="gender" value="male" id="lagna_gender1"> Male
        <input type="radio" name="gender" value="female" id="lagna_gender2" checked> Female
        </div>
    </div>
    <div class="form-group">
        <label for="dob" class="col-sm-2 control-label">Date Of Birth:</label>
        <div class="col-sm-10">
        <input type="text" name="dob" id="datepicker" class="form-control" placeholder="Date Of Birth in Year/Month/Day Format" />
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
    <div class="form-group">
        <label for="dob" class="col-sm-2 control-label">Place Of Birth</label>
        <div class="col-sm-10 ui-widget">
        <input type="text" id="lagna_pob" name="lagna_pob" class="form-control ui-autocomplete-input" placeholder="Enter text for list of places" />
        </div>
    </div>
    <div class="form-group">
        <label for="longitude" class="col-sm-2 control-label">Longitude</label>
        <div class="col-sm-10">
        <input type="text" id="lagna_long_1" class="form-text" name="lon_deg"  />
        <input type="text" id="lagna_long_2" class="form-text" name="lon_min" />
        <select class="select2" id="lagna_long_direction" name="lon_dir">
            <option>E</option>
            <option>W</option>
        </select>
        </div>
    </div>
    <div class="form-group">
        <label for="latitude" class="col-sm-2 control-label">Latitude</label>
        <div class="col-sm-10">
        <input type="text" id="lagna_lat_1" class="form-text" name="lat_deg"  />
        <input type="text" id="lagna_lat_2" class="form-text" name="lat_min" />
        <select class="select2" id="lagna_lat_direction" name="lat_dir">
            <option>N</option>
            <option>S</option>
        </select>
        </div>
    </div>
    <div class="form-group">
        <label for="latitude" class="col-sm-2 control-label">Timezone: <strong>GMT</strong></label>
        <div class="col-sm-10">
            <input type="text" id="lagna_timezone" class="form-text" name="lagna_timezone"  />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10">
            <button type="submit" class="btn btn-primary" name="lagnasubmit"> <!--onclick="javascript:getLagna();return false;"-->Get Lagna</button>
             <button type="reset" class="btn btn-danger">Reset Form</button>
        </div>
    </div>
</form>
 </div>
<div class="spacer"></div>