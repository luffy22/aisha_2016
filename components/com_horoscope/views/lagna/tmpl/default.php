<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
define('_JEXEC', 1);
?>

<form class="form-horizontal" role="form">
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Name:</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="lagna_name" placeholder="Enter your name..." />
        </div>
    </div>
    <div class="form-group">
        <label for="inputGender" class="col-sm-2 control-label">Gender:</label>
        <div class="col-sm-10">
        <input type="radio" name="gender" value="male" id="lagna_gender1"> Male
        <input type="radio" name="gender" value="female" id="lagna_gender2"> Female
        </div>
    </div>
    <div class="form-group">
        <label for="dob" class="col-sm-2 control-label">Date Of Birth:</label>
        <div class="col-sm-10">
        <input type="text" id="datepicker" class="form-control" placeholder="Click to select date of birth" />
        </div>
    </div>
    <div class="form-group">
        <label for="dob" class="col-sm-2 control-label">Time Of Birth:</label>
        <div class="col-sm-10">
        <select class="select2" id="lagna_tob_hr">
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
        <select class="select2" id="lagna_tob_min">
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
        <select class="select2" id="lagna_tob_sec">
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
        <select class="select2" id="lagna_tob_am-pm">
            <option>AM</option>
            <option>PM</option>
        </select>
        </div>
    </div>
    <div class="form-group">
        <label for="dob" class="col-sm-2 control-label">Place Of Birth</label>
        <div class="col-sm-10">
        <input type="text" id="lagna_pob" class="form-control" placeholder="Enter text for list of places" />
        </div>
    </div>
    <div class="form-group">
        <label for="longitude" class="col-sm-2 control-label">Longitude</label>
        <div class="col-sm-10">
        <input type="text" id="lagna_long_1" class="form-text"  />
        <input type="text" id="lagna_long_2" class="form-text" />
        <select class="select2" id="lagna_long_direction">
            <option>E</option>
            <option>W</option>
        </select>
        </div>
    </div>
    <div class="form-group">
        <label for="latitude" class="col-sm-2 control-label">Latitude</label>
        <div class="col-sm-10">
        <input type="text" id="lagna_lat_1" class="form-text"  />
        <input type="text" id="lagna_lat_2" class="form-text" />
        <select class="select2" id="lagna_lat_direction">
            <option>N</option>
            <option>S</option>
        </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10">
            <button type="submit" class="btn btn-primary" onclick="getLagna();return false;">Get Lagna</button>
        </div>
    </div>
</form>
<div class="spacer"></div>