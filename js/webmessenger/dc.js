var inviteLangage = "%USER% invite u to 1 to 1 talk"
/////////////////////////////////////////////////////////
document.onmousemove = function(evt) {
    FC_onMouseMove(evt);
}
document.onmousedown = function(evt) {
    FC_onMouseDown(evt);
}
document.onmouseup = function(evt) {
    FC_onMouseUp(evt);
}
/////////////////////////////////////////////////////////
var FC_InternetExplorer = navigator.appName.indexOf("Microsoft") != -1;
if (webpath == undefined || webpath == "")
{
    var str_url = getURLLink();
    webpath = str_url.substring(0, str_url.lastIndexOf("/"));
}
if (webpath.lastIndexOf("/") == webpath.length - 1) {
    webpath = webpath.substring(0, webpath.length - 1);
}
if (init_surl == undefined || init_surl == "") {
    init_surl = webpath + "/session"
}
var SOCKET_DETECT = 1;
var detectmode = SOCKET_DETECT;
var mainChatFilename = "123webmessenger_chat.swf";
var dameonFilename = "123webmessenger_dc.swf";
var content_url = webpath + "/content.html";
var init_nickname = "";
var ONLINE_STATUS = 1;
var OFFLINE_STATUS = 0;
var FC_windowObject;
var FC_openedWindow = false;
var FC_userStatus = OFFLINE_STATUS;
var popWinCount = 100;
var currentX;
var currentY;
var onPressX;
var onPressY;
var dragName = "";
var WM_CLIENT_TYPE_DC = "0";
var WM_CLIENT_TYPE_WEB = "1";
var WM_CLIENT_TYPE_SWFKIT = "2";
var WM_CLIENT_TYPE_FACEBOOK = "3";
//encode
function b36Encode(str) {
    var char36 = "";
    for (var i = 0; i < str.length; i++) {
        char36 += "U" + str.charCodeAt(i).toString(36);
    }
    return char36;
}
//Decode
function b36Decode(str) {
    str = str.substring(1, str.length);
    var chars = str.split("U");
    for (var i in chars)
        chars[i] = String.fromCharCode(parseInt(chars[i], 36));
    return chars.join("");
}
//
//	DC js start here
function FC_myFlash_DoFSCommand(command, args) {
    var FC_myFlashObj = FC_InternetExplorer ? FC_myFlash : document.FC_myFlash;
}
if (navigator.appName && navigator.appName.indexOf("Microsoft") != -1 &&
        navigator.userAgent.indexOf("Windows") != -1 && navigator.userAgent.indexOf("Windows 3.1") == -1) {
    document.write('<SCRIPT LANGUAGE=VBScript\> \n');
    document.write('on error resume next \n');
    document.write('Sub FC_myFlash_FSCommand(ByVal command, ByVal args)\n');
    document.write(' call FC_myFlash_DoFSCommand(command, args)\n');
    document.write('end sub\n');
    document.write('</SCRIPT\> \n');
}
function dcInit() {
    createDCContainer();
}
function FC_invite_1to1_chat(userid)
{
    if (mcsid != undefined)
    {
        if (typeof topcmm_openChatWindowNotification != 'undefined')
        {
            topcmm_openChatWindowNotification(userid);
        }
        else
        {
            popupInviteWindow(userid, "", WM_CLIENT_TYPE_DC, null);
        }
    }
}
function topcmm_123webmessenger_logout()
{
    topcmm_logout();
}
var clickedFlag = false;
function changeClickedFlag(v) {
    clickedFlag = v
}
function popupInviteWindow(userid, toulv, client_type, obj) {
    if (clickedFlag) {
        setTimeout(function() {
            clickedFlag = false;
        }, 2500)
        return;
    }
    var clientState = getClientState(userid);
    if (null != clientState) {
        if ("CLOSE" == clientState) {
            clickedFlag = true;
            openChatWindow(userid, toulv, client_type, obj);
        } else if ("OPEN" == clientState) {
            return;
        }
    }

}
var mcsid;
var nwcb = false;
function  topcmm_popupChatWindow(obj)
{
    init_user = obj["init_user"];
    init_password = obj["init_password"];
    mcsid = obj["init_mcsid"];
    if (!(init_skin != undefined && init_skin != null && init_skin != "undefined"))
    {
        init_skin = obj["init_skin"];
    }
    nwcb = obj["init_nwcb"];
    popupInviteWindow(obj["invite_uid"], obj["init_toulv"], obj["client_type"], obj);
}
function openChatWindow(userid, toulv, client_type, obj) {
    var winID = String(Math.round(Math.random() * 1E14));
    var vars = "?u=" + b36Encode(init_user) +
            "&k=" + b36Encode(init_password) +
            "&n=" + b36Encode(userid) +
            "&init_mcsid=" + mcsid +
            "&nwcb=" + nwcb +
            "&init_toulv=" + toulv +
            "&init_skin=" + init_skin +
            "&init_language=" + init_language +
            //"&init_logo=" + init_logo +
            //"&init_logo_href=" + init_logo_href +
            "&client_type=" + client_type;

    if (obj != null)
    {
        vars = vars + "&init_user_nickname=" + escape(obj["init_user_nickname"]) +
                "&init_group=" + obj["init_group"] +
                "&invite_nickname=" + escape(obj["invite_nickname"]);
    }
    else
    {
        vars = vars + "&init_group=" + init_group;
    }
    FC_windowObject = popup(content_url + vars, winID, "height=388,width=600,toolbar=no,menubar=no,alwaysRaised=yes,scrollbars=no,resizable=yes,location=no,status=no,alwaysRaised=yes,directories=no,titlebar=no");
    nwcb = false;
    if (FC_windowObject)
    {
        FC_windowObject.focus();
    }
    FC_openedWindow = true;
    if (FC_openedWindow)
    {
        removeInviteWin(userid);
    }
    clickedFlag = false;
}
function popup(url, title, options)
{
    var newWin;
    try
    {
        newWin = window.open(url, title, options);
        if (!newWin)
        {
            newWin = window.open('', title, options);
            if (newWin)
            {
                newWin.location.href = url;
            }
            else
            {
                getElement("topcmm_123flashchat").callBackOpenWindow(url, title, options)
            }
        }
    }
    catch (e)
    {

    }
    return newWin;
}
function setSessionID(value)
{
    mcsid = value;
}
function FC_onGetNewInvite(inviteName, toulv, skin, showStr) {
    window.focus();
    this.focus();
    if (typeof topcmm_openChatWindowNotification == 'undefined')
    {
        popupNewInvite(inviteName, toulv, skin, showStr);
    }
}
function FC_onGetCloseInvite(userName) {
    removeInviteWin(userName);
}
function createDCContainer() {
    var divName = "FC_DC_container";
    var swfWidth = 1;
    var swfHeight = 1;
    //
    var FC_swf_url = webpath + "/" + dameonFilename;
    var FC_flash_vars = "";
    FC_flash_vars += "init_user=" + init_user;
    //FC_flash_vars += "&init_iod=" + init_iod;
    FC_flash_vars += "&init_surl=" + init_surl;
    FC_flash_vars += "&init_host=" + init_host;
    FC_flash_vars += "&init_port=" + init_port;
    FC_flash_vars += "&init_skin=" + init_skin;
    FC_flash_vars += "&init_group=" + init_group;
    if (init_secondary_server_enable) {
        FC_flash_vars += "&sh=" + init_secondary_host;
        FC_flash_vars += "&sp=" + init_secondary_port;
    }
    FC_flash_vars += "&init_password=" + init_password;
    FC_flash_vars += "&init_lang=" + init_language;
    FC_flash_vars += "&detectmode=" + detectmode;
    var FC_swfhtmlcode = "";
    FC_swfhtmlcode += "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http:\/\/download.macromedia.com\/pub\/shockwave\/cabs\/flash\/swflash.cab#version=6,0,0,0\" width=\"" + swfWidth + "\" height=\"" + swfHeight + "\" id=\"FC_listenerSwf\">";
    FC_swfhtmlcode += " <param name=\"movie\" value=\"" + FC_swf_url + "\" \/> ";
    FC_swfhtmlcode += " <param name=\"wmode\" value=\"transparent\" \/>";
    FC_swfhtmlcode += " <param name=\"quality\" value=\"high\" \/>";
    FC_swfhtmlcode += " <param name=\"FlashVars\" value=\"" + FC_flash_vars + "\" \/>";
    FC_swfhtmlcode += " <param name=\"allowScriptAccess\" value=\"always\" \/>";
    FC_swfhtmlcode += " <embed src=\"" + FC_swf_url + "\" FlashVars=\"" + FC_flash_vars + "\" quality=\"high\"  width=\"" + swfWidth + "\" height=\"" + swfHeight + "\" name=\"FC_listenerSwf\" id=\"FC_listenerSwf1\" swLiveConnect=\"true\" allowScriptAccess=\"always\" type=\"application\/x-shockwave-flash\" pluginspage=\"http:\/\/www.macromedia.com\/go\/getflashplayer\"><\/embed>";
    FC_swfhtmlcode += " <\/object>";
    FC_swfhtmlcode = getDivCode(divName, popWinCount++, FC_swfhtmlcode);
    FC_swfhtmlcode += getDivCode("FC_windows", popWinCount++, "");
    document.write(FC_swfhtmlcode);
    var setSwfPositionCyc = setInterval(function() {
        changeDivPosition("FC_windows", 0, 0);
    }, 50);

}
function getDivCode(divName, divZ, html, pos) {
    var str = "";
    str += "<div id=\"" + divName + "\" style=\"";
    str += "left:0px; ";
    str += "top:0px; ";
    if (pos == null) {
        str += "position:absolute; ";
    } else {
        str += "position:" + pos + "; "
    }
    str += "z-index:" + divZ + "; ";
    str += "\">";
    str += html;
    str += "<\/div>";
    return str;
}
function popupNewInvite(inviteName, toulv, skin, showStr) {
    var popWinWidth = 202;
    var popWinTitleHeight = 15;
    var popWinLogoHeight = 35;
    var popWinBodyHeight = 66;
    var totalHeight = popWinTitleHeight + popWinLogoHeight + popWinBodyHeight;
    if (getElement("inviteWin_" + inviteName) == null) {
        if (getElement("container_" + inviteName) == null) {
            var conStr = getDivCode("container_" + inviteName, popWinCount++);
            getElement("FC_windows").innerHTML += conStr;
        }
        if (showStr != null && showStr != "" && showStr != "undefined")
        {
            inviteLangage = showStr;
        }
        var str = "<div id=\"inviteWin_a\" style=\"left:6px; top:27px; position:absolute; z-index:103; \">";
        str += "<div id=\"title\" style=\"width:269px;height:24px;\" ><img src=\"" + webpath + "/skin/" + skin + "/dc.gif\"></div>";
        str += "<div id=\"doment\" align=\"center\" style=\"width:269px;height:106px; background-image:url(" + webpath + "/images/bg-1.gif);\">";
        str += "<div id=\"doment1\" style=\"width:260px;height:66px;padding-top:5px;\"><div id=\"doment3\" style=\" width:233px;height:53px;overflow:auto;overflow-x:hidden;line-height: 15px;border-top:1px solid #D6D6D6; border-left:1px solid #D6D6D6; border-right:1px solid #ECECEC; border-bottom:1px solid #ECECEC;background-color:#F8F8F8;text-align: left; padding:3px; \">";
        str += getReplaceString(inviteLangage, "%USER%", inviteName);
        str += "</div></div>";
        str += "<div id=\"doment2\" align=\"center\" style=\"width:260px;height:25px;\"><img src=\"" + webpath + "/images/btn_1.gif\"  onmouseout=\"this.src='" + webpath + "/images/btn_1.gif';\" onClick=\"javascript:onClickAccept('" + inviteName + "','" + toulv + "');\"";
        str += "onmouseover=\"this.src='" + webpath + "/images/btn_1a.gif';\"></a>&nbsp;&nbsp;&nbsp";
        str += "<img src=\"" + webpath + "/images/btn_2w.gif\"  onmouseout=\"this.src='" + webpath + "/images/btn_2w.gif';\" onClick=\"javascript:onClickDeny('" + inviteName + "','" + toulv + "');\"";
        str += "onmouseover=\"this.src='" + webpath + "/images/btn_2aw.gif';\"></div></div>";
        str = getDivCode("inviteWin_" + inviteName, popWinCount++, str);
        getElement("container_" + inviteName).innerHTML = str;
        changeStaticPosition("inviteWin_" + inviteName, 0, -totalHeight);
        showInviteWindowOut(inviteName);
    }
}
//////////////////
function showInviteWindowOut(userName) {
    var winY = getStaticY("inviteWin_" + userName);
    if (winY < 0) {
        changeStaticPosition("inviteWin_" + userName, 0, winY + 10);
        setTimeout("showInviteWindowOut('" + userName + "');", 50);
    } else {
        changeStaticPosition("inviteWin_" + userName, 0, 0);
    }
}
function FC_onMouseMove(evt) {
    if (FC_IE4) {
        currentX = window.event.clientX;
        currentY = window.event.clientY;
    } else {
        currentX = evt.clientX;
        currentY = evt.clientY;
    }
    if (dragName != "") {
        var x1 = Number(currentX) - Number(onPressX);
        var y1 = Number(currentY) - Number(onPressY);
        if (FC_IE4) {
            changeDivPosition("inviteWin_" + dragName, x1, y1);
        } else {
            changeStaticPosition("inviteWin_" + dragName, x1, y1);
        }
    }
}
function FC_onMouseDown(evt) {
    var id;
    if (FC_IE4) {
        id = window.event.srcElement.id;
    } else {
        id = evt.target.id;
    }
    if (id.indexOf("winTitle") != -1) {
        dragName = id.substring(id.indexOf("_") + 1);
        onPressX = currentX - getElementX("inviteWin_" + dragName);
        onPressY = currentY - getElementY("inviteWin_" + dragName);
    }
}
function FC_onMouseUp(evt) {
    dragName = "";
}
function onClickAccept(userName, toulv) {
    popupInviteWindow(userName, toulv, WM_CLIENT_TYPE_DC);
}
function onClickDeny(userName, toulv) {
    removeInviteWin(userName);
    var dc = getDcMovie();
    dc.denyInvite(userName);
}
function cancelAlert(userName, toulv)
{
//	alert("cancel alert");
    var dc = getDcMovie();
    dc.denyInvite(userName);
}
function removeInviteWin(userName) {
    try {
        getElement("container_" + userName).innerHTML = "";
    } catch (e) {

    }
}
function getElement(winName) {
    if (FC_NS4) {
        return document.layers[winName];
    } else if (FC_NS6) {
        return document.getElementById(winName);
    } else if (FC_IE4) {
        return document.all[winName];
    }
}
function getReplaceString(oldStr, tarStr, newStr) {
    if (oldStr.indexOf(tarStr) != -1) {
        var s1 = oldStr.substring(0, oldStr.indexOf(tarStr));
        var s2 = oldStr.substring(oldStr.indexOf(tarStr) + tarStr.length);
        s1 = getReplaceString(s1, tarStr, newStr);
        s2 = getReplaceString(s2, tarStr, newStr);
        return s1 + newStr + s2;
    } else {
        return oldStr;
    }
}
//
//
function getClientState(id) {
    var dc = getDcMovie();
    var res = "CLOSE"
    try {
        res = dc.getState(id);
    } catch (e) {
    }
    return res;
}
function getDcMovie() {
    var movie;
    if (FC_IE4)
        movie = getElement('FC_listenerSwf');
    else
        movie = getElement('FC_listenerSwf1');
    return movie;
}
function reconnect(receiverID) {
    var dc = getDcMovie();
    dc.reconnect(receiverID);
}
function MM_preloadImages()
{ //v3.0
    var d = document;
    if (d.images) {
        if (!d.MM_p)
            d.MM_p = new Array();
        var i, j = d.MM_p.length, a = MM_preloadImages.arguments;
        for (i = 0; i < a.length; i++)
            if (a[i].indexOf("#") != 0) {
                d.MM_p[j] = new Image;
                d.MM_p[j++].src = a[i];
            }
    }
}
function MM_swapImgRestore()
{ //v3.0
    var i, x, a = document.MM_sr;
    for (i = 0; a && i < a.length && (x = a[i]) && x.oSrc; i++)
        x.src = x.oSrc;
}
function MM_findObj(n, d)
{ //v4.01
    var p, i, x;
    if (!d)
        d = document;
    if ((p = n.indexOf("?")) > 0 && parent.frames.length) {
        d = parent.frames[n.substring(p + 1)].document;
        n = n.substring(0, p);
    }
    if (!(x = d[n]) && d.all)
        x = d.all[n];
    for (i = 0; !x && i < d.forms.length; i++)
        x = d.forms[i][n];
    for (i = 0; !x && d.layers && i < d.layers.length; i++)
        x = MM_findObj(n, d.layers[i].document);
    if (!x && d.getElementById)
        x = d.getElementById(n);
    return x;
}

function MM_swapImage()
{
    var i, j = 0, x, a = MM_swapImage.arguments;
    document.MM_sr = new Array;
    for (i = 0; i < (a.length - 2); i += 3)
        if ((x = MM_findObj(a[i])) != null) {
            document.MM_sr[j++] = x;
            if (!x.oSrc)
                x.oSrc = x.src;
            x.src = a[i + 2];
        }
}