/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// Dropdown menu is closed & opened using this function.
$(document).ready(function()
  {
      //var id = $('.accordion-id').attr('id');
      $('#topcontent-1').accordion({
            heightStyle : "content",
            collapsible : true,
            active      : false
        });
    });
    $(document).ready(function()
  {
      //var id = $('.accordion-id').attr('id');
      $('#topcontent-2').accordion({
            heightStyle : "content",
            collapsible : true,
            active      : false
        });
    });
function buttonCalls()
{
$('#body').css("background","pink");
}
function toggleMenu(id)
{
    var menu_id = id;
    $('#menu_id').dropdown();
}

// Simple function to show the login box
function showLogin()
{
   $('#login-link').css('visibility','hidden');
   $('#login-form').css('visibility', 'visible');
   
   $('#login-form').modal('toggle');
   return false;
}
// Simple function to hide the login box
function hideLogin()
{
   $('#login-link').css('visibility','visible');
   $('#login-form').css('visibility', 'hidden');
   return false;
}

function hideLoginField()
{
    $('#login-link').css('visibility','hidden');
    $('.login-form').css('visibility','visible');
    return false;
}
function validateLogin()
{
    var uname = document.getElementById("al_uname").value;
    var passwd = document.getElementById("al_passwd").value;
   
   form.submit();
}
function validateRegister()
{
    var uname   = document.getElementById("ar_uname");
    var passwd  = document.getElementById("ar_passwd");
    var cpasswd = document.getElementById("ar_cpasswd");
    var email   = document.getElementById("ar_email");
    
    if(uname.value==""||uname.value.length<5||uname.value.length>14)
    {
        alert("Enter 5-14 length alpha-numeric username");
        return false;
    }
    else if(passwd.value==""||passwd.value.length<5||passwd.value.length>14)
    {
        alert("Enter 5-14 length alpha-numeric username");
        return false;
    }
    else if(passwd.value != cpasswd.value)
    {
        alert("Passwords do not match");
        return false;
    }
    else if(email.value=="")
    {
        alert("Please enter a valid email");
        return false;
    }
    else
    {
        form.submit();
        return true;
    }
}

function LoginUser()
{
    document.getElementById("loadergif").style.display = 'block';
    var moduname        = document.getElementById("mod-uname");
    var modpwd          = document.getElementById("mod-pwd"); 
    //var modcred         = jQuery.parseJSON('{"uname":moduname.value,"pwd":modpwd.value}');
    
    var request = jQuery.ajax({
    url: "index.php?option=com_ajax&module=droplogin&format=raw&method=LoginUser",
    data: "uname="+moduname.value+"&pwd="+modpwd.value,
    dataType: "text"
});
   request.done(function(msg)
   {
       if(msg=='invalid')
       {
           document.getElementById('error-msg').style.visibility = "visible";
           document.getElementById("error-msg").innerHTML = "Invalid Login Credentials";
           document.getElementById("loadergif").style.display = 'none';
       }
       else if(msg=='no-auth')
       {
           document.getElementById('error-msg').style.visibility = "visible";
           document.getElementById("error-msg").innerHTML = "Please confirm email to register";
           document.getElementById("loadergif").style.display = 'none';
       }
       else
       {
           document.getElementById("login-cred").innerHTML = msg;
           document.getElementById("loadergif").style.display = 'none';
       }
   });
   request.fail(function()
   {
       document.getElementById("login-cred").innerHTML  = "Unable to Fetch Login Credentials...";
       document.getElementById("loadergif").style.display = 'none';
   }); 
}

function getGirlsNakshatra()
{
    document.getElementById('match_message').innerHTML = "";
    document.getElementById('match_message').style.background = "none";
    document.getElementById("match_message").style.background = "none";
    document.getElementById("loadergif").style.display = 'block';
    var g_rashi  = document.getElementById("g_rashi");
    g_rashi.style.background = "none";
    if(g_rashi.value=="")
    {
        document.getElementById("g_rashi_notice").innerHTML = "Please select a rashi for girl";
        document.getElementById("loadergif").style.display = 'none';
    }
    else
    {
        var request = jQuery.ajax({
         url: "ajaxcalls/nakshatracalc.php",
        data: "g_rashi="+g_rashi.value,
        dataType: "text"
        });
        request.done(function(msg)
        {
            if(msg=="error")
            {
                document.getElementById('g_nakshatra_notice').innerHTML = "Failed to fetch data";
                document.getElementById("loadergif").style.display = 'none';
            }
            else
            {
                document.getElementById('g_nakshatra').innerHTML = msg;
                document.getElementById("loadergif").style.display = 'none';
            }
        });
        request.fail(function()
        {
            alert("Fail to get data");
             document.getElementById("loadergif").style.display = 'none';
        });
    }
}
/*function getGirlsPada()
{
    var g_nakshatra  = document.getElementById("g_nakshatra");
    var g_rashi      = document.getElementById("g_rashi");
    document.getElementById("loadergif").style.display = 'block';
    g_nakshatra.style.background = "none";
    if(g_nakshatra.value=="")
    {
        g_nakshatra.style.background = "#FF0000";
        document.getElementById("g_nakshatra_notice").innerHTML = "Please select a nakshatra for girl";
        document.getElementById("loadergif").style.display = 'none';
    }
    else if(g_rashi.value=="")
    {
        g_rashi.style.background = "#FF0000";
        document.getElementById("g_rashi_notice").innerHTML = "Please select a rashi for girl";
        document.getElementById("loadergif").style.display = 'none';
    }
    else
    {
        var request = jQuery.ajax({
         url: "index.php?option=com_ajax&module=nakshatracompat&format=raw&method=GirlPada",
        data: "g_nakshatra="+g_nakshatra.value+'&g_rashi='+g_rashi.value,
        dataType: "text"
        });
        request.done(function(msg)
        {
            document.getElementById('g_pada').innerHTML = msg;
            document.getElementById("loadergif").style.display = 'none';
        });
        request.fail(function()
        {
            alert("Fail to get data");
            document.getElementById("loadergif").style.display = 'none';
        });
    }
}*/
function getBoysNakshatra()
{
    document.getElementById('match_message').innerHTML = "";
    document.getElementById('match_message').style.background = "none";
    document.getElementById("loadergif").style.display = 'block';
    var b_rashi  = document.getElementById("b_rashi");
    b_rashi.style.background = "none";
    if(b_rashi.value=="")
    {
        b_rashi.style.background = "#FF0000";
        document.getElementById("b_rashi_notice").innerHTML = "Please select a rashi for girl";
        document.getElementById("loadergif").style.display = 'none';
    }
    else
    {
        var request = jQuery.ajax({
        url: "ajaxcalls/nakshatracalc.php",
        data: "b_rashi="+b_rashi.value,
        dataType: "text"
        });
        request.done(function(msg)
        {
            if(msg=="error")
            {
                document.getElementById('b_nakshatra_notice').innerHTML = "Failed to fetch data";
                document.getElementById("loadergif").style.display = 'none';
            }
            else
            {
                document.getElementById('b_nakshatra').innerHTML = msg;
                document.getElementById("loadergif").style.display = 'none';
            }
        });
        request.fail(function()
        {
            alert("Fail to get data");
            document.getElementById("loadergif").style.display = 'none';
        });
    }
}
/*function getBoysPada()
{
    var b_nakshatra  = document.getElementById("b_nakshatra");
    var b_rashi      = document.getElementById("b_rashi");
    document.getElementById("loadergif").style.display = 'block';
    b_nakshatra.style.background = "none";
    if(b_nakshatra.value=="")
    {
        b_nakshatra.style.background = "#FF0000";
        document.getElementById("b_nakshatra_notice").innerHTML = "Please select a nakshatra for girl";
        document.getElementById("loadergif").style.display = 'none';
    }
    else if(b_rashi.value=="")
    {
        b_rashi.style.background = "#FF0000";
        document.getElementById("b_rashi_notice").innerHTML = "Please select a rashi for girl";
        document.getElementById("loadergif").style.display = 'none';
    }
    else
    {
        var request = jQuery.ajax({
         url: "index.php?option=com_ajax&module=nakshatracompat&format=raw&method=BoyPada",
        data: "b_nakshatra="+b_nakshatra.value+'&b_rashi='+b_rashi.value,
        dataType: "text"
        });
        request.done(function(msg)
        {
            document.getElementById('b_pada').innerHTML = msg;
            document.getElementById("loadergif").style.display = 'none';
        });
        request.fail(function()
        {
            alert("Fail to get data");
            document.getElementById("loadergif").style.display = 'none';
        });
    }
}
*/
function checkCompatibility()
{
    var g_1     = document.getElementById("g_rashi");
    var g_2         = document.getElementById("g_nakshatra");
    var b_1     = document.getElementById("b_rashi");
    var b_2         = document.getElementById("b_nakshatra");
    document.getElementById("loadergif").style.display = 'block';
    if(g_1.value==""||g_2.value=="")
    {
        alert("One of the values is missing for girl");
        document.getElementById("loadergif").style.display = 'none';
    }
    else if(b_1.value==""||b_2.value=="")
    {
        alert("One of the values is missing for boy");
        document.getElementById("loadergif").style.display = 'none';
    }
    else
    {
        var request = jQuery.ajax({
        url: "ajaxcalls/nakshatracalc.php",
        data: "g_1="+g_1.value+'&g_2='+g_2.value+"&b_1="+b_1.value+'&b_2='+b_2.value,
        dataType: "text"
        });
        request.done(function(msg)
        {
                document.getElementById("loadergif").style.display = 'none';
              if(msg=="error")
              {
                  alert("Fail to get data");
              }
              else
              {
                var pts = parseInt(msg);
                document.getElementById("loadergif").style.display = 'none';
                if(pts<18)
                {
                    document.getElementById('match_message').innerHTML = "Match Points are "+msg+" (Not Good Match)";
                    document.getElementById("match_message").style.background = "#FF0000";
                }
                else if(pts>=18&&pts<=28)
                {
                    document.getElementById('match_message').innerHTML = "Match Points are "+msg+" (Decent Match)";
                    document.getElementById("match_message").style.background = "#FFFF00";
                }
                else if(pts>28)
                {
                    document.getElementById('match_message').innerHTML = "Match Points are "+msg+" (Good Match)";
                    document.getElementById("match_message").style.background = "#00FF00";
                }
              }
        });
        request.fail(function()
        {
            alert("Fail to get data");
            document.getElementById("loadergif").style.display = 'none';
        });
    }
}
function getLagna()
{
    var lagna   = document.getElementById("lagna_1");
    var gender  = document.getElementById("lagna_gender1");
    var dob     = document.getElementById("datepicker");
    /*if(lagna.value=="")
    {
        
        $("#lagna_grp_1").addClass("has-error has-feedback");
        $("#lagna_ico_1").addClass("glyphicon glyphicon-remove");
        document.getElementById('lagna_err_1').style.visibility = "visible";
    }*/
}
/*     
 *     var location = window.location.protocol + "//" + window.location.host;
*/
$(function() 
{
   var result       = "";
   $( "#lagna_pob" ).autocomplete({
      source: 
       function(request, response) {
        $.ajax({
          url: "ajaxcalls/autocomplete.php",
          dataType: "json",
          data: {
            term: request.term
          },
          success: function( data ) {
          response(data);
          
          }
        
        });
      },
      minLength: 3,
      select: function(request, response)
      {
            var lat           = response.item.lat;
            var lon           = response.item.lon;
            var tmz           = response.item.tmz;
            var lat_dir       = lat.substring(0,1);
            var lat_deg       = lat.split(".")[0];
            var lat_min       = lat.split(".")[1].substr(0,2);
            var lon_dir       = lon.substring(0,1);
            var lon_deg       = lon.split(".")[0];
            var lon_min       = lon.split(".")[1].substr(0,2);
            document.getElementById("lagna_timezone").value = tmz;
            if(lon_dir == "-")
            {
                document.getElementById("lagna_long_direction").value = "W";
                document.getElementById("lagna_long_1").value = lon_deg.slice(1);
                document.getElementById("lagna_long_2").value = lon_min;
            }
            else
            {
                document.getElementById("lagna_long_direction").value = "E";
                document.getElementById("lagna_long_1").value = lon_deg;
                document.getElementById("lagna_long_2").value = lon_min;
            }
                
            if(lat_dir == "-")
            {
                document.getElementById("lagna_lat_direction").value = "S";
                document.getElementById("lagna_lat_1").value = lat_deg.slice(1);
                document.getElementById("lagna_lat_2").value = lat_min;
            }
            else
            {
                document.getElementById("lagna_lat_direction").value = "N";
                document.getElementById("lagna_lat_1").value = lat_deg;
                document.getElementById("lagna_lat_2").value = lat_min;
            }
      },
      open: function() {
        $('#lagna_pob').removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
         $(".ui-autocomplete").css("z-index", 1000);
      },
      close: function() {
        $('#lagna_pob').removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
     
    });
});
 /*success: function(data) {
           
            
            alert(result);
          }*/

 $(function() {
    $( "#oppos-rashi" ).accordion({
      heightStyle: "content",
      collapsible: true
    });
  });
  
  $(document).ready(function()
  {
      var id = $('.accordion-id').attr('id');
      $('#accordion-'+id).accordion({
            heightStyle: "content",
            collapsible: true,
            active : false
        });
    });
 $(document).ready(function()
  {
      var id = $('.lagna_find').attr('id');
      $('#accordion-'+id).accordion({
            heightStyle: "content",
            collapsible: true,
            active      : false
        });
    });
    
function showSideMenu()
{
    $('#sidebar').removeClass('hidden-md hidden-xs');
    $('#sidebar').toggle(1200);
}
$(function() 
{
   var result       = "";
   $( "#ques_pob" ).autocomplete({
      source: 
       function(request, response) {
        $.ajax({
          url: "ajaxcalls/autocomplete.php",
          dataType: "json",
          data: {
            term: request.term
          },
          success: function( data ) {
          response(data);
          
          }
        
        });
      },
      minLength: 3,
     
      open: function() {
        $('#ques_pob').removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
         $(".ui-autocomplete").css("z-index", 1000);
      },
      close: function() {
        $('#ques_pob').removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
   })
   
});
$(function() {
$( "#ques_dob" ).datepicker({ yearRange: "1900:2050",changeMonth: true,
  changeYear: true, dateFormat: "yy/mm/dd"  });
});

function checkDetails()
{
   var name             = document.getElementById("ques_1");
   var email            = document.getElementById("ques_2");
   var pob              = document.getElementById("ques_pob");
   var dob              = document.getElementById("ques_dob");
   var email_regex      = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
   $('#ques_err_1').css('visibility','hidden');
   $('#ques_err_2').css('visibility','hidden');
   $('#ques_err_4').css('visibility','hidden');
   $('#ques_err_5').css('visibility','hidden');
   $('#ques_grp_1').removeClass(" has-error");
   $('#ques_grp_2').removeClass(" has-error");
   $('#ques_grp_4').removeClass(" has-error");
   $('#ques_grp_5').removeClass(" has-error");
   if(name.value=="")
   {
       $('#ques_grp_1').addClass(" has-error");
       $('#ques_err_1').css('visibility','visible');
   }
   else if(email.value=="")
   {
       $('#ques_grp_2').addClass(" has-error");
       $('#ques_err_2').css('visibility','visible');
   }
   else if(dob.value=="")
   {
       $('#ques_grp_4').addClass(" has-error");
       $('#ques_err_4').css('visibility','visible');
   }
   else if(pob.value=="")
   {
       $('#ques_grp_5').addClass(" has-error");
       $('#ques_err_5').css('visibility','visible');
   }
   else
   {
       $('#ques_page_1').css('visibility','hidden');
       $('#ques_page_2').css('visibility','visible');
       $('#ques_page_1').hide();
       $('#ques_page_2').show();
   }
}
function explainChoice()
{
    if(document.getElementById("ques_explain").value=="detail")
    {
        document.getElementById("ques_grp_7").innerHTML = 
            "<h3>Detailed Explanation</h3><p>Answer would be more thorough and after examining minute details related to the question. Causes and Remedial Measures would be provided in detail. Follow Up questions related to subject would be answered.</p>";
        if((document.getElementById("ques_explain").value=="detail")&&(document.getElementById("ques_choice").value=="1"))
        {
            document.getElementById("ques_grp_7").innerHTML += "<p><strong>Total: 300 "+"<html>&#8377;</html>"+"</strong></p>";
        }
        if((document.getElementById("ques_explain").value=="detail")&&(document.getElementById("ques_choice").value=="2"))
        {
            document.getElementById("ques_grp_7").innerHTML += "<p><strong>Total: 600 "+"<html>&#8377;</html>"+"</strong></p>";
        }
        if((document.getElementById("ques_explain").value=="detail")&&(document.getElementById("ques_choice").value=="3"))
        {
            document.getElementById("ques_grp_7").innerHTML += "<p><strong>Total: 900 "+"<html>&#8377;</html>"+"</strong></p>";
        }
        
    }
    else if(document.getElementById("ques_explain").value=="short")
    {
        document.getElementById("ques_grp_7").innerHTML = 
            "<h3>Short Explanation</h3><p>Answer would be brief and to the point. No remedial measures or causes would be explained unless asked in the question itself. No follow up questions would be entertained.</p>";
        if((document.getElementById("ques_explain").value=="short")&&(document.getElementById("ques_choice").value=="1"))
        {
            document.getElementById("ques_grp_7").innerHTML += "<p><strong>Total: 100 "+"<html>&#8377;</html>"+"</strong></p>";
        }
        else if((document.getElementById("ques_explain").value=="short")&&(document.getElementById("ques_choice").value=="2"))
        {
            document.getElementById("ques_grp_7").innerHTML += "<p><strong>Total: 200 "+"<html>&#8377;</html>"+"</strong></p>";
        }
        else if((document.getElementById("ques_explain").value=="short")&&(document.getElementById("ques_choice").value=="3"))
        {
            document.getElementById("ques_grp_7").innerHTML += "<p><strong>Total: 300 "+"<html>&#8377;</html>"+"</strong></p>";
        }
    }
    else if(document.getElementById("ques_explain").value=="none")
    {
       document.getElementById("ques_grp_7").innerHTML = "none";
    }
    
}
function backPage()
{
    $('#ques_page_1').css('visibility','visible');
    $('#ques_page_2').css('visibility','hidden');
    $('#ques_page_3').css('visibility','hidden');
    $('#ques_page_1').show();
    $('#ques_page_2').hide();
    $('#ques_page_3').hide();
}
function backPage1()
{
    $('#ques_page_1').css('visibility','hidden');
    $('#ques_page_2').css('visibility','visible');
    $('#ques_page_3').css('visibility','hidden');
    $('#ques_page_1').hide();
    $('#ques_page_2').show();
    $('#ques_page_3').hide();
}
function nextPage()
{
    if(document.getElementById("ques_explain").value=="none")
    {
        alert("One of the values is missing.");
    }
    else
    {
        $("#loadergif2").css('display','block');
        $('#ques_page_1').css('visibility','hidden');
        $('#ques_page_2').css('visibility','hidden');
        $('#ques_page_3').css('visibility','visible');
        $('#ques_page_1').hide();
        $('#ques_page_2').hide();
        $('#ques_page_3').show();
        
        if((document.getElementById("ques_explain").value=="detail")&&(document.getElementById("ques_choice").value=="1"))
        {
            $( "#ques-content" ).load( "ajaxcalls/1questiondetail.html" );
            $("#loadergif2").css('display','none');
        }
        else if((document.getElementById("ques_explain").value=="short")&&(document.getElementById("ques_choice").value=="1"))
        {
            $( "#ques-content" ).load( "ajaxcalls/1questionshort.html" );
            $("#loadergif2").css('display','none');
        }
        else if((document.getElementById("ques_explain").value=="short")&&(document.getElementById("ques_choice").value=="2"))
        {
            $( "#ques-content" ).load( "ajaxcalls/2questionshort.html" );
            $("#loadergif2").css('display','none');
        }
        else if((document.getElementById("ques_explain").value=="detail")&&(document.getElementById("ques_choice").value=="2"))
        {
            $( "#ques-content" ).load( "ajaxcalls/2questiondetail.html" );
            $("#loadergif2").css('display','none');
        }
        else if((document.getElementById("ques_explain").value=="short")&&(document.getElementById("ques_choice").value=="3"))
        {
            $( "#ques-content" ).load( "ajaxcalls/3questionshort.html" );
            $("#loadergif2").css('display','none');
        }
        else if((document.getElementById("ques_explain").value=="detail")&&(document.getElementById("ques_choice").value=="3"))
        {
            $( "#ques-content" ).load( "ajaxcalls/3questiondetail.html" );
            $("#loadergif2").css('display','none');
        }
    }
}
function nextPage1()
{
     if((document.getElementById("ques_explain").value=="detail")&&(document.getElementById("ques_choice").value=="1"))
     {
         if(document.getElementById("ques_ask_1").value=="")
         {
             
         }
     }
}