
// ---------------------------------------  online user function ----------------------------------------- //
/*var xmlHttp
 function dsp_online_members(user_id)
 {
 var main_path=document.getElementById("main_path").value;
 //alert(main_path);
 var session=document.getElementById("session_id").value;
 xmlHttp=GetXmlHttpObject();
 if (xmlHttp==null)
 {
 alert ("Your browser does not support AJAX!");
 return;
 } 
 //alert(str);
 var url=main_path+"dsp_online_access.php";
 url=url+"?session_id="+session+"&user_id="+user_id;
 //alert(url);
 //url=url+"&sid="+Math.random();
 xmlHttp.onreadystatechange=RegionChanged;
 xmlHttp.open("GET",url,true);
 xmlHttp.send(null);
 }
 function RegionChanged() 
 { 
 if (xmlHttp.readyState==4)
 { 
 
 //alert(xmlHttp.responseText);
 document.getElementById("location_div").innerHTML=xmlHttp.responseText;
 
 }
 }
 function GetXmlHttpObject()
 {
 var xmlHttp=null;
 try
 {
 // Firefox, Opera 8.0+, Safari
 xmlHttp=new XMLHttpRequest();
 }
 catch (e)
 {
 // Internet Explorer
 try
 {
 xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
 }
 catch (e)
 {
 xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
 }
 }
 return xmlHttp;
 }
 */
// ---------------------  FUNCTION USE IN DSP USER SECTION MY PAGE ---------------------------------------//
function update_blocked_member()
{
    var loc = window.location.href;
//alert(loc);
    document.getElementById("block_event").value = "blocked";
    document.action = loc;
    document.block_memberfrm.submit();
} // End update_blocked_member()
function delete_blocked_member(blocked_id)
{
    if (confirm("Are you sure you want to delete?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&Block_Id=" + blocked_id;
        window.location.href = loc;
    }
} // End delete_blocked_member()
function update_title_status()
{
    var loc = window.location.href;
//alert(loc);
    document.getElementById("txtmode").value = "update_title";
    document.action = loc;
    document.updatetitlefrm.submit();
} // End update_title_status()
function dsp_update_profile()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    alert(loc);
    document.getElementById("txtupdatemode").value = "update";
    alert(document.getElementById("txtupdatemode").value);
    document.action = loc;
    document.updateprofilefrm.submit();
} // End dsp_update_profile()
// ---------------------------------------------------------------------------------------------------------- //
// ---------------------  FUNCTION USE IN DSP USER SECTION ADD NEW PHOTOS ALBUM ---------------------------------------//
function add_photos_album()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    document.getElementById("txtalbummode").value = "add_album";
    document.action = loc;
    document.createalbum.submit();
} // End add_photos_album()
function delete_photos_album(album_id)
{
    if (confirm("Are you sure you want to delete this Album?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&album_Id=" + album_id;
        window.location.href = loc;
    }
} // End delete_photos_album()
function update_photos_album(album_id)
{
    if (confirm("Are you sure you want to update this Album?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=update&album_Id=" + album_id;
        window.location.href = loc;
    }
} // End update_photos_album()
// ---------------------------------------------------------------------------------------------------------- //
// ---------------------  FUNCTION USE IN DSP USER SECTION ADD NEW PHOTOS ALBUM ---------------------------------------//
function add_photos_in_album()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    document.action = loc;
    document.frmuploadimage.submit();
} // End add_photos_in_album()
// ------------------------------------------------------------------------------------------------------------ //
// ---------------  FUNCTION USE IN DSP USER SECTION DELETE AND UPDATE PHOTO OF SELECTED ALBUM -----------------//
function delete_user_photo(user_photo_id)
{
    if (confirm("Are you sure you want to delete this Picture?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&picture_Id=" + user_photo_id;
        window.location.href = loc;
    }
} // End delete_user_photo()
// ---------------------  FUNCTION USE IN DSP USER SECTION Compose Email ---------------------------------------//
function send_email_function()
{
    var loc = window.location.href;
    alert(loc + 'ddd');
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    alert(loc);
    document.action = loc;
    document.composefrm.submit();
} // End send_email_function()
// ------------------------------------------------------------------------------------------------------------ //
// ---------------------  FUNCTION USE IN DSP USER SECTION EDIT PROIFILE---------------------------------------//
/*$(document).ready(function() {
 $('form#profilefrm').submit(function() {
 $('form#profilefrm .error').remove();
 var hasError = false;
 $('.requiredField').each(function() {
 if(jQuery.trim($(this).val()) == '') {
 var labelText = $(this).prev('label').text();
 $(this).parent().append('<span class="error">You forgot to select your '+labelText+'.</span>');
 hasError = true;
 } else if($(this).hasClass('email')) {
 var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
 if(!emailReg.test(jQuery.trim($(this).val()))) {
 var labelText = $(this).prev('label').text();
 $(this).parent().append('<span class="error">You entered an invalid '+labelText+'.</span>');
 hasError = true;
 }
 }
 });
 if(!hasError) {
 $('form#profilefrm li.buttons button').fadeOut('normal', function() {
 $(this).parent().append('<img src="/wp-content/themes/modxblog/images/loading.gif" alt="Loading&hellip;" height="31" width="31" />');
 });
 var formInput = $(this).serialize();
 $.post($(this).attr('action'),formInput, function(data){
 $('form#profilefrm').slideUp("fast", function() {				   
 $(this).before('<p class="thanks"><strong>Thanks!</strong> Your profile successfully updated.</p>');
 });
 });
 }
 return false;
 });
 });*/
// ---------------------------------------------------------------------------------------------------------- //
// -------------------------function to use in quick search widget form-------------------------- //
function search_by_quick_widget()
{
    var loc = window.location.href;
    if (loc.search("pagetitle") > -1)
    {
        index = loc.indexOf("pagetitle")
        loc = loc.substring(0, index - 1);
    }
    document.action = loc;
    document.frmsearch.submit();
} // End search_by_quick_widget()
// -------------------------------------------------------------------------------------------- //
// ----------------------------------  DELETE SAVE SEARCHES   ---------------------------------- //
function delete_save_search(save_search_id)
{
    if (confirm("Are you sure you want to delete?"))
    {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&search_Id=" + save_search_id;
        window.location.href = loc;
    }
} // End delete_save_search()
// -------------------------------------------------------------------------------------------- //
// ----------------------------------  FETCH RECORD BY SAVE SEARCHES  ---------------------------------- //
function save_search_record(save_search_id)
{
    var loc = window.location.href;
    if (loc.search("pagetitle") > -1)
    {
        index = loc.indexOf("pagetitle")
        loc = loc.substring(0, index - 1);
    }
    document.getElementById("save_search_Id").value = save_search_id;
    document.action = loc;
    document.savesearches.submit();
} // End save_search_record()
// ----------------------------------  FETCH RECORD BY SAVE SEARCHES  ---------------------------------- //
function delete_favourites_member(favourite_id)
{
    if (confirm("Are you sure you want to delete?"))
    {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&favourite_Id=" + favourite_id;
        window.location.href = loc;
    }
} // End delete_favourites_member()
// -------------------------------------------------------------------------------------------- //
// ----------------------------------  function perform on (friend request) ---------------------------------- //
function arrove_frnd_request(frnd_req_id)
{
    if (confirm("Are you sure you want to Approve selected friend request?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=approve&frnd_request_Id=" + frnd_req_id;
        window.location.href = loc;
    }
} // End arrove_frnd_request()
function reject_frnd_request(frnd_req_id)
{
    if (confirm("Are you sure you want to Reject selected friend request?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=reject&frnd_request_Id=" + frnd_req_id;
        window.location.href = loc;
    }
} // End reject_frnd_request()
function delete_friend_from_list(del_frnd_id)
{
    if (confirm("Are you sure you want to Delete selected Member?"))
    {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&friend_Id=" + del_frnd_id;
        window.location.href = loc;
    }
} // End delete_friend_from_list()
// -------------------------------------------------------------------------------------------- //
function delete_dsp_emails()
{
    if (confirm("Are you sure you want to Delete?"))
    {
        var loc = window.location.href;
        alert(loc);
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        document.getElementById("mode").value = "sent";
        document.action = loc;
        document.frmdelmessages.submit();
    }
} // End delete_dsp_emails()
function reply_dsp_emails()
{
    var loc = window.location.href;

    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    document.getElementById("mode").value = "sent";
    document.action = loc;
    document.frmdelmessages.submit();

} // End delete_dsp_emails()
// -------------------------function use in Guest Search-------------------------- //
function dsp_guest_search()
{
    var loc = window.location.href;
    if (loc.search("pagetitle") > -1)
    {
        index = loc.indexOf("pagetitle")
        loc = loc.substring(0, index - 1);
    }
    document.action = loc;
    document.frmguestsearch.submit();
} // End dsp_guest_search()()
// ------------------------------------------------------------------------------- //
// -------------------------function use in wink message-------------------------- //
function send_wink_message()
{
    var loc = window.location.href;
//alert(loc);
    document.action = loc;
    document.sendwinkfrm.submit();
} // End send_wink_message()()
function delete_wink_message(wink_msg_id)
{
    if (confirm("Are you sure you want to delete?"))
    {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&del_wink_Id=" + wink_msg_id;
        window.location.href = loc;
    }
} // End delete_wink_message()
// ------------------------------------------------------------------------------- //
// --------------------------------------  VIEW MEMBER PICTURE,FRIENDS,AND PROFILE -------------------------------//
function member_details(id, str)
{
    if (id) {
        var loc = window.location.href;
        if (loc.search("pagetitle") > -1)
        {
            index = loc.indexOf("pagetitle")
            loc = loc.substring(0, index - 1);
        }
        if (str == 'profile') {
            loc += "&pagetitle=view_profile";
        }
        else if (str == 'album') {
            loc += "&pagetitle=view_album";
        }
        else if (str == 'friends') {
            loc += "&pagetitle=view_friends";
        }
        window.location.href = loc;
    }
}
function member_pictures(id, str, album_id)
{
    if (id) {
        var loc = window.location.href;
        if (loc.search("pagetitle") > -1)
        {
            index = loc.indexOf("pagetitle")
            loc = loc.substring(0, index - 1);
        }
        if (str == 'pictures') {
            loc += "&pagetitle=view_Pictures&album_id=" + album_id;
        }
        window.location.href = loc;
    }
}
//---------------------------------------------------------------------------------------------------------------//
// ---------------------------------- Ajax function to show country ,state and city --------------------------//
function GetXmlHttpObject()
{
    var xmlHttp = null;
    try
    {
        // Firefox, Opera 8.0+, Safari
        xmlHttp = new XMLHttpRequest();
    }
    catch (e)
    {
        // Internet Explorer
        try
        {
            xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");

        }
        catch (e)
        {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
    }

    //alert(xmlHttp);
    return xmlHttp;
}
var xmlHttp
function Show_state(country_id) {
    //alert("Test");
    document.getElementById("load_img_id").style.display = "inline";
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Your browser does not support AJAX!");
        return;
    }
    var loc = window.location.href;
    if (loc.search("pid") > -1)
    {
        index = loc.indexOf("pid")
        loc = loc.substring(0, index - 1);
    }
    var url = loc;
    url = url + "?pid=11&country_id=" + country_id;
    //alert(url);
    //url=url+"&sid="+Math.random();
    xmlHttp.onreadystatechange = plateChanged;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}
function Show_city(state_id) {
//alert(state_id);
    document.getElementById("load_img_id2").style.display = "inline";
    ;
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Your browser does not support AJAX!");
        return;
    }
    var loc = window.location.href;
    if (loc.search("pid") > -1)
    {
        index = loc.indexOf("pid")
        loc = loc.substring(0, index - 1);
    }
    var url = loc;
    url = url + "?pid=11&state_id=" + state_id;
    //alert(url);
    //alert(url);
    //url=url+"&sid="+Math.random();
    xmlHttp.onreadystatechange = plateChanged2;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}
function plateChanged2()
{
    if (xmlHttp.readyState == 4)
    {
        document.getElementById("city_div").innerHTML = xmlHttp.responseText;
        var content = document.getElementById("citydropdown");
        content.innerHTML = "";
        objSt = document.getElementById("cmbCity");
        content.appendChild(objSt);
        objimg2 = document.getElementById("load_img_id2");
        content.appendChild(objimg2);
        document.getElementById("city_div").innerHTML = "";

    }
}
function plateChanged()
{
    if (xmlHttp.readyState == 4)
    {
        document.getElementById("state_div").innerHTML = xmlHttp.responseText;
        var content = document.getElementById("statedropdown");
        content.innerHTML = "";
        objSt = document.getElementById("cmbState");
        content.appendChild(objSt);
        objimg = document.getElementById("load_img_id");
        content.appendChild(objimg);
        document.getElementById("state_div").innerHTML = "";
    }
}
function Show_state2(country_id) {
    document.getElementById("load_img_id").style.display = "inline";
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Your browser does not support AJAX!");
        return;
    }
    var loc = window.location.href;
    if (loc.search("pgurl") > -1)
    {
        index = loc.indexOf("pgurl")
        loc = loc.substring(0, index - 1);
    }
    var url = loc;
    url = url + "?pgurl=11&country_id=" + country_id;
    //alert(url);
    //url=url+"&sid="+Math.random();
    xmlHttp.onreadystatechange = plateChangedn1;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}
function Show_city2(state_id) {
//alert(state_id);
    document.getElementById("load_img_id2").style.display = "inline";
    ;
    xmlHttp = GetXmlHttpObject();
    if (xmlHttp == null)
    {
        alert("Your browser does not support AJAX!");
        return;
    }
    var loc = window.location.href;
    if (loc.search("pgurl") > -1)
    {
        index = loc.indexOf("pgurl")
        loc = loc.substring(0, index - 1);
    }
    var url = loc;
    url = url + "?pgurl=11&state_id=" + state_id;
    //alert(url);
    //alert(url);
    //url=url+"&sid="+Math.random();
    xmlHttp.onreadystatechange = plateChangedn2;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}
function plateChangedn2()
{
    if (xmlHttp.readyState == 4)
    {
        document.getElementById("city_div").innerHTML = xmlHttp.responseText;
        var content = document.getElementById("citydropdown");
        content.innerHTML = "";
        objSt = document.getElementById("cmbCity");
        content.appendChild(objSt);
        objimg2 = document.getElementById("load_img_id2");
        content.appendChild(objimg2);
        document.getElementById("city_div").innerHTML = "";

    }
}
function plateChangedn1()
{
    if (xmlHttp.readyState == 4)
    {
        document.getElementById("state_div").innerHTML = xmlHttp.responseText;
        var content = document.getElementById("statedropdown");
        content.innerHTML = "";
        objSt = document.getElementById("cmbState");
        content.appendChild(objSt);
        objimg = document.getElementById("load_img_id");
        content.appendChild(objimg);
        document.getElementById("state_div").innerHTML = "";
    }
}
// -------------------------------------------  DELETE AUDIO BY USER ---------------------------------- // 
function dsp_delete_audio(audio_file_id)
{
    if (confirm("Are you sure you want to delete this Audio file?"))
    {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&audio_Id=" + audio_file_id;
        window.location.href = loc;
    }
} // End dsp_delete_audio()
// -------------------------------------------  DELETE AUDIO BY USER ---------------------------------- // 
// -------------------------------------------  DELETE Video BY USER ---------------------------------- // 
function dsp_delete_video(video_file_id)
{
    if (confirm("Are you sure you want to delete this Video?"))
    {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&video_Id=" + video_file_id;
        window.location.href = loc;
    }
} // End dsp_delete_video()
// -------------------------------------------  DELETE Video BY USER ---------------------------------- // 
//---------------------------------Function for Login Page---------------------------------------------//
function getTextBox()
{
    document.getElementById('resetPwd').style.display = 'table-row';
    document.getElementById('resetPwdsub').style.display = 'table-row';

}
//----------------------------------------------END OF LOGIN PAGE FUNCTION-------------------------------//
//function func1(country_id,state_id)
//{
//	alert(country_id);
//	alert(state_id);
//}
//----------------------------------------------DELETE BLOG FUNCTION-------------------------------//
function delete_blog(blog_id)
{
    if (confirm("Are you sure you want to delete this Image?"))
    {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&blog_id=" + blog_id;
        window.location.href = loc;
    }
} // End delete_blog()
function update_blog(blog_id)
{
    if (confirm("Are you sure you want to update this Album?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=update&blog_id=" + blog_id;
        window.location.href = loc;
    }
}
//----------------------------------------------END DELETE BLOG FUNCTION-------------------------------//
function stealth_mode(user_id, stealth_mode)
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    loc += "&Action=update&smid=" + user_id + "&smode=" + stealth_mode;
    window.location.href = loc;
}
function report_member(id)
{
    alert(id);
    if (confirm("Are you sure you want to report this profile?")) {
    }
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    loc += "&Action=report&mid=" + id;
    window.location.href = loc;
} // End report_member()
