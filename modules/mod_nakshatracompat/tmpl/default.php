<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');
?>
<h3>Nakshatra Compatibility Calculator</h3>
<div class="row">
    <div class="col-md-6">
        <div id="g_rashi_notice"><label>Choose Girls Rashi</label></div>
        <select id="g_rashi" name="girls_rashi" class="selectcomp form-control" onclick="javascript:getGirlsNakshatra();">
            <option value="" default="default">Girls Rashi</option>
            <option value="aries">Mesha(Aries)</option>
            <option value="taurus">Vrisabha(Taurus)</option>
            <option value="gemini">Mithuna(Gemini)</option>
            <option value="cancer">Karka(Cancer)</option>
            <option value="leo">Simha(Leo)</option>
            <option value="virgo">Kanya(Virgo)</option>
            <option value="libra">Tula(Libra)</option>
            <option value="scorpio">Vrischika(Scorpio)</option>
            <option value="saggitarius">Dhana(Saggitarius)</option>
            <option value="capricorn">Makara(Capricorn)</option>
            <option value="aquarius">Kumbha(Aquarius)</option>
            <option value="pisces">Meena(Pisces)</option>
        </select>
    </div>
    <div class="col-md-6">
        <div id="b_rashi_notice"><label>Choose Boys Rashi</label></div>
        <select id="b_rashi" name="boys_rashi" class="selectcomp form-control" onclick="javascript:getBoysNakshatra();">
            <option value="" default="default">Boys Rashi</option>
            <option value="aries">Mesha(Aries)</option>
            <option value="taurus">Vrisabha(Taurus)</option>
            <option value="gemini">Mithuna(Gemini)</option>
            <option value="cancer">Karka(Cancer)</option>
            <option value="leo">Simha(Leo)</option>
            <option value="virgo">Kanya(Virgo)</option>
            <option value="libra">Tula(Libra)</option>
            <option value="scorpio">Vrischika(Scorpio)</option>
            <option value="saggitarius">Dhana(Saggitarius)</option>
            <option value="capricorn">Makara(Capricorn)</option>
            <option value="aquarius">Kumbha(Aquarius)</option>
            <option value="pisces">Meena(Pisces)</option>
        </select>
    </div>
</div>
<div class="mini-spacer"></div>
<div class="row">
    <div class="col-md-6">
        <div id="nakshatra_calc">
            <div id="g_nakshtra_notice"><label>Choose Girls Nakshatra</label></div>
            <select id="g_nakshatra" name="girls_nakshatra" class="selectcomp form-control" onclick="getGirlsPada();">
                <option value="" default="default">Select Default</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div id="nakshatra_b_calc">
            <div id="b_nakshtra_notice"><label>Choose Boys Nakshatra</label></div>
            <select id="b_nakshatra" name="boys_nakshatra" class="selectcomp form-control" onclick="getBoysPada();">
                <option value="" default="default">Select Default</option>
            </select>
        </div>
    </div> 
</div>
<div class="mini-spacer"></div>
<div class="row">
    <div class="col-md-6">
        <div id="g_pada_notice"><label>Choose Girls Pada</label></div>
        <select id="g_pada" name="girls_pada" class="selectcomp form-control">
            <option value="" default="default">Select Default</option>
        </select>
    </div>
    <div class="col-md-6">
        <div id="b_pada_notice"><label>Choose Boys Pada</label></div>
        <select id="b_pada" name="girls_pada" class="selectcomp form-control">
            <option value="" default="default">Select Default</option>
        </select>
    </div>
</div>
<div class="mini-spacer"></div>
<div class="row">
    <div class="col-md-6">
        <button class="btn btn-primary" id="compat-submit" onclick="checkCompatibility()">Check Compatibility</button>
    </div>
</div>
<div class="mini-spacer"></div>
<div class="row">
    <div class="col-xs-12">
        <div class="error" id="match_message"></div>
    </div>
</div>
