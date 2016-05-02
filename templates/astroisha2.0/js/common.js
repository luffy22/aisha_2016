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
function toggleMenu(id)
{
    var menu_id = id;
    $('#menu_id').dropdown();
}
function getLagna()
{
    var inputs = document.getElementsByTagName('input');
    var lagna   = document.getElementById("lagna_1");
    var dob     = document.getElementById("datepicker");
    var pob     = document.getElementById("lagna_pob");
    for(var i=1;i<=inputs.length;i++)
    {
        $("#lagna_grp_"+i).removeClass("has-error has-feedback");
        $("#lagna_ico_"+i).removeClass("glyphicon glyphicon-remove");
        $("#lagna_err_"+i).attr("style","visibility:hidden");
    }
    if(lagna.value=="")
    {
        
        $("#lagna_grp_1").addClass("has-error has-feedback");
        $("#lagna_ico_1").addClass("glyphicon glyphicon-remove");
        document.getElementById('lagna_err_1').style.visibility = "visible";
    }
    else if(dob.value=="")
    {
        $("#lagna_grp_3").addClass("has-error has-feedback");
        $("#lagna_ico_3").addClass("glyphicon glyphicon-remove");
        document.getElementById('lagna_err_3').style.visibility = "visible";
    }
     else if(pob.value=="")
    {
        $("#lagna_grp_4").addClass("has-error has-feedback");
        $("#lagna_ico_4").addClass("glyphicon glyphicon-remove");
        document.getElementById('lagna_err_4').style.visibility = "visible";
    }
    else
    {
        form.submit();
    }
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
$( "#ques_dob" ).datepicker({yearRange: "1900:2050",changeMonth: true,
  changeYear: true, dateFormat: "yy/mm/dd"});
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
    var location = document.getElementById("user_loc");
    if(location.value=="IN"&&document.getElementById("user_curr").value=="INR")
    {
        var currency_detail = 300*parseInt(document.getElementById("ques_choice").value);
        var currency_small  = 100*parseInt(document.getElementById("ques_choice").value);
    }
    else if(location.value=="UK"&&document.getElementById("user_curr").value=="GBP")
    {
        var currency_detail = 10*parseInt(document.getElementById("ques_choice").value);
        var currency_small  = 3.5*parseInt(document.getElementById("ques_choice").value);
    }
    else if(location.value=="US"&&document.getElementById("user_curr").value=="USD")
    {
        var currency_detail = 15*parseInt(document.getElementById("ques_choice").value);
        var currency_small  = 5*parseInt(document.getElementById("ques_choice").value);
    }
    else if(location.value=="AU"&&document.getElementById("user_curr").value=="AUD")
    {
        var currency_detail = 15*parseInt(document.getElementById("ques_choice").value);
        var currency_small  = 5*parseInt(document.getElementById("ques_choice").value);
    }
    else if(location.value=="CA"&&document.getElementById("user_curr").value=="CAD")
    {
        var currency_detail = 15*parseInt(document.getElementById("ques_choice").value);
        var currency_small  = 5*parseInt(document.getElementById("ques_choice").value);
    }
    else if(location.value=="NZ"&&document.getElementById("user_curr").value=="NZD")
    {
        var currency_detail = 15*parseInt(document.getElementById("ques_choice").value);
        var currency_small  = 5*parseInt(document.getElementById("ques_choice").value);
    }
    else if(location.value=="SG"&&document.getElementById("user_curr").value=="SGD")
    {
        var currency_detail = 15*parseInt(document.getElementById("ques_choice").value);
        var currency_small  = 5*parseInt(document.getElementById("ques_choice").value);
    }
    else if(location.value=="LK"||location.value=="PK"||location.value=="BD"||
            location.value=="NP"||location.value=="ID")
    {
        var currency_detail = 5*parseInt(document.getElementById("ques_choice").value);
        var currency_small  = 1.5*parseInt(document.getElementById("ques_choice").value);
    }
    else
    {
        var currency_detail = 10*parseInt(document.getElementById("ques_choice").value);
        var currency_small  = 3.5*parseInt(document.getElementById("ques_choice").value);
    }
    if((document.getElementById("ques_explain").value=="detail")&&(location.value=="SG"||
    location.value=="NZ"||location.value=="CA"||location.value=="CA"||location.value=="AU"||
    location.value=="US"||location.value=="UK"||location.value=="IN"))
    {
        document.getElementById("ques_grp_7").innerHTML = 
        "<h3>Detailed Explanation</h3><p>Answer would be more thorough and after examining minute details related to the question. Causes and Remedial Measures would be provided in detail. Follow Up questions related to subject would be answered.</p>";
        document.getElementById("ques_grp_7").innerHTML += "<p><strong>Total: "+currency_detail+" "+document.getElementById("user_curr").value+" ("+document.getElementById("user_curr_full").value+")";
        document.getElementById("ques_charge").value        = currency_detail;
    }
    else if((document.getElementById("ques_explain").value=="detail")&&(location.value!=="SG"||
    location.value!=="NZ"||location.value!=="CA"||location.value!=="AU"||
    location.value!=="US"||location.value=="UK"||location.value!=="IN"))
    {
        document.getElementById("ques_grp_7").innerHTML = 
        "<h3>Detailed Explanation</h3><p>Answer would be more thorough and after examining minute details related to the question. Causes and Remedial Measures would be provided in detail. Follow Up questions related to subject would be answered.</p>";
        document.getElementById("ques_grp_7").innerHTML += "<p><strong>Total: "+currency_detail+" $(US Dollars)";
        document.getElementById("ques_charge").value        = currency_detail;
        document.getElementById("user_curr").value      = "USD";
        document.getElementById("user_curr_full").value       = "US Dollar";
    }
    else if((document.getElementById("ques_explain").value=="short")&&(location.value=="SG"||
    location.value=="NZ"||location.value=="CA"||location.value=="CA"||location.value=="AU"||
    location.value=="US"||location.value=="UK"||location.value=="IN"))
    {
        document.getElementById("ques_grp_7").innerHTML = 
        "<h3>Short Explanation</h3><p>Answer would be brief and to the point. No remedial measures or causes would be explained unless asked in the question itself. No follow up questions would be entertained.</p>";
        document.getElementById("ques_grp_7").innerHTML += "<p><strong>Total: "+currency_small+" "+document.getElementById("user_curr").value+" ("+document.getElementById("user_curr_full").value+")";  
        document.getElementById("ques_charge").value        = currency_small;
    }
    else if((document.getElementById("ques_explain").value=="short")&&(location.value!=="SG"||
    location.value!=="NZ"||location.value=="CA"||location.value!=="CA"||location.value!=="AU"||
    location.value!=="US"||location.value=="UK"||location.value!=="IN"))
    {
        document.getElementById("ques_grp_7").innerHTML = 
        "<h3>Short Explanation</h3><p>Answer would be brief and to the point. No remedial measures or causes would be explained unless asked in the question itself. No follow up questions would be entertained.</p>";
        document.getElementById("ques_grp_7").innerHTML += "<p><strong>Total: "+currency_small+" $(US Dollars)"; 
        document.getElementById("ques_charge").value        = currency_small;
        document.getElementById("user_curr").value      = "USD";
        document.getElementById("user_curr_full").value       = "US Dollar";
    }
    else if(document.getElementById("ques_explain").value=="none")
    {
       document.getElementById("ques_grp_7").innerHTML = "";
    }
    
}

function backPage()
{
    $('#ques_page_1').css('visibility','visible');
    $('#ques_page_2').css('visibility','hidden');
    $('#ques_page_3').css('visibility','hidden');
    $('#ques_page_4').css('visibility','hidden');
    $('#ques_page_1').show();
    $('#ques_page_2').hide();
    $('#ques_page_3').hide();
    $('#ques_page_4').hide();
}
function backPage1()
{
    $('#ques_page_1').css('visibility','hidden');
    $('#ques_page_2').css('visibility','visible');
    $('#ques_page_3').css('visibility','hidden');
    $('#ques_page_4').css('visibility','hidden');
    $('#ques_page_1').hide();
    $('#ques_page_2').show();
    $('#ques_page_3').hide();
    $('#ques_page_4').hide();
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
        $("#loadergif2").css('visibility','visible');
        $('#ques_page_1').css('visibility','hidden');
        $('#ques_page_2').css('visibility','hidden');
        $('#ques_page_3').css('visibility','visible');
        $('#ques_page_4').css('visibility','hidden');
        $('#ques_page_1').hide();
        $('#ques_page_2').hide();
        $('#ques_page_3').show();
        $('#ques_page_4').hide();
        
        if((document.getElementById("ques_explain").value=="detail")&&(document.getElementById("ques_choice").value=="1"))
        {
            $( "#ques-content" ).load( "ajaxcalls/1questiondetail.html" );
            $("#loadergif2").css('display','none');
            $("#loadergif2").css('visibility','hidden');
        }
        else if((document.getElementById("ques_explain").value=="short")&&(document.getElementById("ques_choice").value=="1"))
        {
            $( "#ques-content" ).load( "ajaxcalls/1questionshort.html" );
            $("#loadergif2").css('display','none');
            $("#loadergif2").css('visibility','hidden');
        }
        else if((document.getElementById("ques_explain").value=="short")&&(document.getElementById("ques_choice").value=="2"))
        {
            $( "#ques-content" ).load( "ajaxcalls/2questionshort.html" );
            $("#loadergif2").css('display','none');
            $("#loadergif2").css('visibility','hidden');
        }
        else if((document.getElementById("ques_explain").value=="detail")&&(document.getElementById("ques_choice").value=="2"))
        {
            $( "#ques-content" ).load( "ajaxcalls/2questiondetail.html" );
            $("#loadergif2").css('display','none');
            $("#loadergif2").css('visibility','hidden');
        }
        else if((document.getElementById("ques_explain").value=="short")&&(document.getElementById("ques_choice").value=="3"))
        {
            $( "#ques-content" ).load( "ajaxcalls/3questionshort.html" );
            $("#loadergif2").css('display','none');
            $("#loadergif2").css('visibility','hidden');
        }
        else if((document.getElementById("ques_explain").value=="detail")&&(document.getElementById("ques_choice").value=="3"))
        {
            $( "#ques-content" ).load( "ajaxcalls/3questiondetail.html" );
            $("#loadergif2").css('display','none');
            $("#loadergif2").css('visibility','hidden');
        }
    }
}
function nextPage1()
{
     if((document.getElementById("ques_explain").value=="short")&&(document.getElementById("ques_choice").value=="1"))
     {
         if(document.getElementById("ques_ask_1").value=="")
         {
            ques_grp_1_open();
            ques_txt1_open();
            return false;
         }
         else
         {
            ques_grp_1_close();
            ques_txt1_close();
            documenent.getElementById("ques_form").submit();
            return true;
         }
        
     }
     else if((document.getElementById("ques_explain").value=="short")&&(document.getElementById("ques_choice").value=="2"))
     {
         if(document.getElementById("ques_ask_1").value=="")
         {
            ques_grp_1_open();
            ques_txt1_open();
            ques_grp_2_close();
            ques_txt2_close();
            return false;
         }
         else if(document.getElementById("ques_ask_2").value=="")
         {
            ques_grp_1_close();
            ques_txt1_close();
            ques_grp_2_open();
            ques_txt2_open();
            return false;
         }
         else
         {
            ques_grp_2_close();
            ques_grp_1_close();
            ques_txt1_close();
            ques_txt2_close();
            documenent.getElementById("ques_form").submit();
            return true;
         }
    }
     else if((document.getElementById("ques_explain").value=="short")&&(document.getElementById("ques_choice").value=="3"))
     {
         if(document.getElementById("ques_ask_1").value=="")
         {
            ques_grp_1_open();
            ques_txt1_open();
            ques_grp_2_close();
            ques_txt2_close();
            ques_grp_3_close();
            ques_txt3_close();
            return false;
         }
         else if(document.getElementById("ques_ask_2").value=="")
         {
            ques_grp_1_close();
            ques_txt1_close();
            ques_grp_2_open();
            ques_txt2_open();
            ques_grp_3_close();
            ques_txt3_close();
            return false;
         }
         else if(document.getElementById("ques_ask_3").value=="")
         {
            ques_grp_1_close();
            ques_txt1_close();
            ques_grp_2_close();
            ques_txt2_close();
            ques_grp_3_open();
            ques_txt3_open();
            return false;
         }
         else
         {
            ques_grp_1_close();
            ques_txt1_close();
            ques_grp_2_close();
            ques_txt2_close();
            ques_grp_3_close();
            ques_txt3_close();
            documenent.getElementById("ques_form").submit();
            return true;
         }
     }
     else if((document.getElementById("ques_explain").value=="detail")&&(document.getElementById("ques_choice").value=="1"))
     {
         if(document.getElementById("ques_ask_1").value=="")
         {
            ques_grp_1_open();
            ques_txt1_open();
            ques_det1_close();
            return false;
         }
         else if(document.getElementById("ques_detail_1").value=="")
         {
            ques_grp_1_open();
            ques_txt1_close();
            ques_det1_open();
            return false;
         }
         else
         {
            ques_grp_1_close();
            ques_det1_close();
            ques_txt1_close();
            document.getElementById("ques_form").submit();
            return true;
         }
     }
     else if((document.getElementById("ques_explain").value=="detail")&&(document.getElementById("ques_choice").value=="2"))
     {
         if(document.getElementById("ques_ask_1").value=="")
         {
            ques_grp_1_open();
            ques_txt1_open();
            ques_det1_close();
            ques_grp_2_close();
            ques_txt2_close();
            ques_det2_close();
            return false;

         }
         else if(document.getElementById("ques_detail_1").value=="")
         {
            ques_grp_1_open();
            ques_txt1_close();
            ques_det1_open();
            ques_grp_2_close();
            ques_txt2_close();
            ques_det2_close();
            return false;
         }
         else if(document.getElementById("ques_ask_2").value=="")
         {
            ques_grp_1_close();
            ques_txt1_close();
            ques_det1_close();
            ques_grp_2_open();
            ques_txt2_open();
            ques_det2_close();
            return false;
         }
         else if(document.getElementById("ques_detail_2").value=="")
         {
            ques_grp_1_close();
            ques_txt1_close();
            ques_det1_close();
            ques_grp_2_open();
            ques_txt2_close();
            ques_det2_open();
            return false;
         }
         else
         {
            ques_grp_1_close();
            ques_txt1_close();
            ques_det1_close();
            ques_grp_2_close();
            ques_txt2_close();
            ques_det2_close();
            documenent.getElementById("ques_form").submit();
            return true;
         }
     }
    else if((document.getElementById("ques_explain").value=="detail")&&(document.getElementById("ques_choice").value=="3"))
     {
         if(document.getElementById("ques_ask_1").value=="")
         {
            ques_grp_1_open();
            ques_txt1_open();
            ques_det1_close();
            ques_grp_2_close();
            ques_txt2_close();
            ques_det2_close();
            ques_grp_3_close();
            ques_txt3_close();
            ques_det3_close();
            return false;
         }
         else if(document.getElementById("ques_detail_1").value=="")
         {
            ques_grp_1_open();
            ques_txt1_close();
            ques_det1_open();
            ques_grp_2_close();
            ques_txt2_close();
            ques_det2_close();
            ques_grp_3_close();
            ques_txt3_close();
            ques_det3_close();
            return false;
         }
         else if(document.getElementById("ques_ask_2").value=="")
         {
            ques_grp_1_close();
            ques_txt1_close();
            ques_det1_close();
            ques_grp_2_open();
            ques_txt2_open();
            ques_det2_close();
            ques_grp_3_close();
            ques_txt3_close();
            ques_det3_close();
            return false;

         }
         else if(document.getElementById("ques_detail_2").value=="")
         {
            ques_grp_1_close();
            ques_txt1_close();
            ques_det1_close();
            ques_grp_2_open();
            ques_txt2_close();
            ques_det2_open();
            ques_grp_3_close();
            ques_txt3_close();
            ques_det3_close();
            return false;
         }
         else if(document.getElementById("ques_ask_3").value=="")
         {
            ques_grp_1_close();
            ques_txt1_close();
            ques_det1_close();
            ques_grp_2_close();
            ques_txt2_close();
            ques_det2_close();
            ques_grp_3_open();
            ques_txt3_open();
            ques_det3_close();
            return false;
         }
         else if(document.getElementById("ques_detail_3").value=="")
         {
            ques_grp_1_close();
            ques_txt1_close();
            ques_det1_close();
            ques_grp_2_close();
            ques_txt2_close();
            ques_det2_close();
            ques_grp_3_open();
            ques_txt3_close();
            ques_det3_open();
            return false;
         }
         else
         {
            ques_grp_1_close();
            ques_txt1_close();
            ques_det1_close();
            ques_grp_2_close();
            ques_txt2_close();
            ques_det2_close();
            ques_grp_3_close();
            ques_txt3_close();
            ques_det3_close();
            document.getElementById("ques_form").submit();
            return true;
         }
     }
     
}
function ques_grp_1_open()
{
    $('#collapseOne').addClass(" in");
}
function ques_grp_1_close()
{
    $('#collapseOne').removeClass(" in");
}
function ques_grp_2_open()
{
    $('#collapseTwo').addClass(" in");
}
function ques_grp_2_close()
{
    $('#collapseTwo').removeClass(" in");
}
function ques_grp_3_open()
{
    $('#collapseThree').addClass(" in");
}
function ques_grp_3_close()
{
    $('#collapseThree').removeClass(" in");
}
function ques_txt1_open()
{
    $('#ques_grp_ask1').addClass(" has-error");
    $('#ques_err_ask1').css('visibility','visible');
}
function ques_txt1_close()
{
    $('#ques_grp_ask1').removeClass(" has-error");
    $('#ques_err_ask1').css('visibility','hidden');
}
function ques_txt2_open()
{
    $('#ques_grp_ask2').addClass(" has-error");
    $('#ques_err_ask2').css('visibility','visible');
}
function ques_txt2_close()
{
    $('#ques_grp_ask2').removeClass(" has-error");
    $('#ques_err_ask2').css('visibility','hidden');
}

function ques_txt3_open()
{
    $('#ques_grp_ask3').addClass(" has-error");
    $('#ques_err_ask3').css('visibility','visible');
}

function ques_txt3_close()
{
    $('#ques_grp_ask3').removeClass(" has-error");
    $('#ques_err_ask3').css('visibility','hidden');
}
function ques_det1_open()
{
    $('#ques_grp_det1').addClass(" has-error");
    $('#ques_err_det1').css('visibility','visible');
}
function ques_det1_close()
{
    $('#ques_grp_det1').removeClass(" has-error");
    $('#ques_err_det1').css('visibility','hidden');
}
function ques_det2_open()
{
    $('#ques_grp_det2').addClass(" has-error");
    $('#ques_err_det2').css('visibility','visible');
    
}
function ques_det2_close()
{
    $('#ques_grp_det2').removeClass(" has-error");
    $('#ques_err_det2').css('visibility','hidden');
}
function ques_det3_open()
{
    $('#ques_grp_det3').addClass(" has-error");
    $('#ques_err_det3').css('visibility','visible');
}
function ques_det3_close()
{
    $('#ques_grp_det3').removeClass(" has-error");
    $('#ques_err_det3').css('visibility','hidden');
}

