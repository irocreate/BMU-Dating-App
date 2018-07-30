
function blacklistip(ip_status, user_id, ip_address) {

    if (confirm("Are you sure that you want to blacklist this user IP?"))
    {
        var loc = window.location.href;

        if (loc.search("mode") > -1)
        {
            index = loc.indexOf("mode")
            loc = loc.substring(0, index - 1);
        }
        loc += "&mode=update&uid=" + user_id + "&ip=" + ip_address + "&ip_status=" + ip_status;
        window.location.href = loc;
    }
}
// ---------------------  function use in DSP Admin Media -- dsp_media_photos ---------------------------------------
function delete_images(photo_id)
{
    if (confirm("Are you sure you want to delete this Image?"))
    {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&Id=" + photo_id;
        window.location.href = loc;
    }
} // End delete_images()
function delete_approve_images(photo_id)
{
    if (confirm("Are you sure you want to delete this Image?"))
    {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Delete&Id=" + photo_id;
        window.location.href = loc;
    }
} // End delete_images()
function approve_images(photo_id)
{
    if (confirm("Are you sure you want to Approve this Image?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=approve&Id=" + photo_id;
        window.location.href = loc;
    }
} // End delete_images()
function reject_images(photo_id)
{
    if (confirm("Are you sure you want to Reject this Image?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=reject&Id=" + photo_id;
        window.location.href = loc;
    }
} // End reject_images()
//  -----------------------------------------------------------------------------------------------------------------//
// ---------------------  function use in DSP Admin Settings -- dsp_settings_memberships ---------------------------//
function delete_memberships(membership_id)
{
    if (confirm("Are you sure you want to delete this Membership?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&Id=" + membership_id;
        window.location.href = loc;
    }
} // End delete_memberships()
function update_memberships(membership_id)
{
    if (confirm("Are you sure you want to update this Membership?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=update&Id=" + membership_id;
        window.location.href = loc;
    }
} // End update_memberships()
function add_memberships()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    document.action = loc;
    document.membershipfrm.submit();
} // End add_memberships()
//  -----------------------------------------------------------------------------------------------------------------//
// ---------------------  function use in DSP Admin Settings -- dsp_settings_memberships ---------------------------//
function add_spam_words()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
// alert(loc);
    document.action = loc;
    document.frmspamwords.submit();
} // End add_spam_words()
function delete_spamword(spam_word_id)
{
    if (confirm("Are you sure you want to delete this Spam?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&Id=" + spam_word_id;
        window.location.href = loc;
    }
} // End delete_memberships()
function update_spamword(spam_word_id)
{
    if (confirm("Are you sure you want to update this Spam?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=update&Id=" + spam_word_id;
        window.location.href = loc;
    }
} // End update_spamword()
function update_spam_filters()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    document.action = loc;
    document.frmspamfilter.submit();
} // End update_spam_filters()
//  -----------------------------------------------------------------------------------------------------------------//
// ---------------------  function use in DSP Admin Tools -- dsp_tools_profiles ------------------------------------//
function add_profile_question()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
// alert(loc);
    document.action = loc;
    document.frmaddquestions.submit();
} // End add_profile_question()
function delete_profile_question(profile_question_id)
{
    if (confirm("Are you sure you want to delete this profile Question?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&Id=" + profile_question_id;
        window.location.href = loc;
    }
} // End delete_profile_question()
function update_profile_question(profile_question_id)
{
    if (confirm("Are you sure you want to update this profile Question?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=update&Id=" + profile_question_id;
        window.location.href = loc;
    }
} // End update_profile_question()
//  -----------------------------------------------------------------------------------------------------------------//
// ---------------------  function use in DSP Admin Tools -- dsp_tools_profiles_OPTION ------------------------------------//
function add_profile_question_option()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
// alert(loc);
    document.action = loc;
    document.frmaddoptions.submit();
} // End add_profile_question_option()
function delete_profile_question_option(profile_question_option_id)
{
    if (confirm("Are you sure you want to delete this option?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&opt_Id=" + profile_question_option_id;
        window.location.href = loc;
    }
} // End delete_profile_question_option()
function update_profile_question_option(profile_question_option_id)
{
    if (confirm("Are you sure you want to update this option?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=update&opt_Id=" + profile_question_option_id;
        window.location.href = loc;
    }
} // End update_profile_question_option()
// ---------------------------------  function to use add flirt,update,delete in tools submenu -------------------------- //
function add_flirt_text()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
// alert(loc);
    document.action = loc;
    document.frmflirttext.submit();
} // End add_flirt_text()
function delete_flirt_text(flirt_id)
{
    if (confirm("Are you sure you want to delete this flirt?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&flirt_Id=" + flirt_id;
        window.location.href = loc;
    }
} // End delete_flirt_text()
function update_flirt_text(flirt_id)
{
    if (confirm("Are you sure you want to update this flirt?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=update&flirt_Id=" + flirt_id;
        window.location.href = loc;
    }
} // End update_flirt_text()
//-----------------------------------------------------------------------------------------------------------------------//
//-------------------------------------------  function use in premium access settings ---------------------------------- //
function add_dsp_feature()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    //alert(loc);
    document.getElementById("feature_mode").value = "add_feature";
    document.action = loc;
    document.addfeaturefrm.submit();
} // End add_dsp_feature()
function delete_access_feature(acess_feature_id)
{
    if (confirm("Are you sure you want to delete?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&ft_ID=" + acess_feature_id;
        window.location.href = loc;
    }
} // End delete_access_feature()
function update_access_feature()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    //alert(loc);
    document.getElementById("update_feature_mode").value = "update_feature_mode";
    document.action = loc;
    document.updatefeaturesfrm.submit();
} // End update_access_feature()
//--------------------------------------------------------------------------------------------------------------------- //
//-----------------------------------------Update Email Template------------------------------------------------- //
function update_email_template_text(mail_template_id)
{
    if (confirm("Are you sure you want to update?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=update&Mail_temp_ID=" + mail_template_id;
        window.location.href = loc;
    }
} // End update_email_template_text()
function add_email_template_text()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
// alert(loc);
    document.action = loc;
    document.frmemailtemplates.submit();
} // End add_email_template_text()
function dsp_reset_fields()
{
    document.getElementById("subjectid").value = "";
    document.getElementById("mailbodyid").value = "";
} // End dsp_reset_fields() 
// ---------------------------------- Add,edit and delete country ,state and city --------------------------//
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
    return xmlHttp;
}
var xmlHttp
function Show(country_id) {
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
    url = url + "&pid=fetch_state&country_id=" + country_id;
    //url=url+"&sid="+Math.random();
    xmlHttp.onreadystatechange = plateChanged;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}
function Show2(state_id) {
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
    url = url + "&pid=fetch_state&state_id=" + state_id;
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
function add_dsp_country()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    document.add_dsp_geography.update_geo.value = "add_country";
    //alert(loc);
    document.action = loc;
    document.add_dsp_geography.submit();
} // End add_dsp_country()
function delete_dsp_country()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    document.add_dsp_geography.update_geo.value = "delete_country";
    //alert(loc);
    document.action = loc;
    document.add_dsp_geography.submit();
} // End delete_dsp_country()
function update_dsp_country()
{
    var objsel = document.getElementById("cmbCountry");
    var country_id = objsel.options[objsel.selectedIndex].value;
    var text = objsel.options[objsel.selectedIndex].innerHTML;
    document.getElementById("edit_country_id").value = country_id;
    document.getElementById("txtcounty").value = text;
    document.getElementById("country_button").value = "Update";
} // End update_dsp_country()
function add_dsp_state()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    document.add_dsp_geography.update_geo.value = "add_state";
    //alert(loc);
    document.action = loc;
    document.add_dsp_geography.submit();
} // End add_dsp_state()
function delete_dsp_state()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    document.add_dsp_geography.update_geo.value = "delete_state";
    //alert(loc);
    document.action = loc;
    document.add_dsp_geography.submit();
} // End delete_dsp_state()
function update_dsp_state()
{
    var objsel = document.getElementById("cmbState");
    var state_id = objsel.options[objsel.selectedIndex].value;
    var text = objsel.options[objsel.selectedIndex].innerHTML;
    document.getElementById("edit_state_id").value = state_id;
    document.getElementById("txtstate").value = text;
    document.getElementById("state_button").value = "Update";
} // End update_dsp_state()
function add_dsp_city()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    document.add_dsp_geography.update_geo.value = "add_city";
    //alert(loc);
    document.action = loc;
    document.add_dsp_geography.submit();
} // End add_dsp_state()
function delete_dsp_city()
{
    var loc = window.location.href;
    if (loc.search("Action") > -1)
    {
        index = loc.indexOf("Action")
        loc = loc.substring(0, index - 1);
    }
    document.add_dsp_geography.update_geo.value = "delete_city";
    document.action = loc;
    document.add_dsp_geography.submit();
} // End delete_dsp_city()
function update_dsp_city()
{
    var objsel = document.getElementById("cmbCity");
    var city_id = objsel.options[objsel.selectedIndex].value;
    var text = objsel.options[objsel.selectedIndex].innerHTML;
    document.getElementById("edit_city_id").value = city_id;
    document.getElementById("txtcity").value = text;
    document.getElementById("city_button").value = "Update";
} // End update_dsp_city()
function dsp_update_now()
{
    var loc = window.location.href;
    var objsel1 = document.getElementById("cmbCountry");
    var country_id = objsel1.options[objsel1.selectedIndex].value;
    var objsel2 = document.getElementById("cmbState");
    var state_id = objsel2.options[objsel2.selectedIndex].value;
    var objsel3 = document.getElementById("cmbCity");
    var city_id = objsel3.options[objsel3.selectedIndex].value;
    document.getElementById("edit_city_id").value = city_id;
    document.getElementById("edit_state_id").value = state_id;
    document.getElementById("edit_country_id").value = country_id;
    document.add_dsp_geography.update_geo.value = "update_now";
    document.action = loc;
    document.add_dsp_geography.submit();
}
//--------------------------------------------------------------------------------------------------------------- //
// --------------------  Ajax function use in edit country,state and city by admin -------------------------------//
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
    return xmlHttp;
}
var xmlHttp
function Show_state_e(country_id) {
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

    url = url + "&pid=media_profile_view_geo&country_id=" + country_id;
    //alert(url);
    //url=url+"&sid="+Math.random();
    xmlHttp.onreadystatechange = plateChanged;
    xmlHttp.open("GET", url, true);
    xmlHttp.send(null);
}
function Show_city_e(state_id) {
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
    url = url + "&pid=media_profile_view_geo&state_id=" + state_id;
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
// ---------------------  function use in DSP Admin Media -- dsp_media_audios ---------------------------------------
function delete_audio(audio_file_id)
{
    if (confirm("Are you sure you want to delete this Audio file?"))
    {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&Id=" + audio_file_id;
        window.location.href = loc;
    }
} // End delete_audio()
//  delete_approve_audio()
function delete_approve_audio(audio_file_id)
{
    if (confirm("Are you sure you want to delete this Audio file?"))
    {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Delete&Id=" + audio_file_id;
        window.location.href = loc;
    }
} // End delete_audio()
function approve_audio(audio_file_id)
{
    if (confirm("Are you sure you want to Approve this Audio file?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=approve&Id=" + audio_file_id;
        window.location.href = loc;
    }
} // End approve_audio()
function reject_audio(audio_file_id)
{
    if (confirm("Are you sure you want to Reject this Audio file?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=reject&Id=" + audio_file_id;
        window.location.href = loc;
    }
} // End reject_audio()
//  -----------------------------------------------------------------------------------------------------------------//
// ---------------------  function use in DSP Admin Media -- dsp_media_videos ---------------------------------------
function delete_video(video_file_id)
{
    if (confirm("Are you sure you want to delete this Video file?"))
    {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Del&Id=" + video_file_id;
        window.location.href = loc;
    }
} // End delete_video()
function delete_approve_video(video_file_id)
{
    if (confirm("Are you sure you want to delete this Video file?"))
    {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=Delete&Id=" + video_file_id;
        window.location.href = loc;
    }
} // End delete_video()
function approve_video(video_file_id)
{
    if (confirm("Are you sure you want to Approve this Video file?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=approve&Id=" + video_file_id;
        window.location.href = loc;
    }
} // End approve_video()
function reject_video(video_file_id)
{
    if (confirm("Are you sure you want to Reject this Video file?")) {
        var loc = window.location.href;
        if (loc.search("Action") > -1)
        {
            index = loc.indexOf("Action")
            loc = loc.substring(0, index - 1);
        }
        loc += "&Action=reject&Id=" + video_file_id;
        window.location.href = loc;
    }
} // End reject_video()
//  -----------------------------------------------------------------------------------------------------------------//
