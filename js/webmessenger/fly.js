// This script is copyright (c) Henrik Petersen, NetKontoret
// Feel free to use this script on your own pages as long as you do not change it.
// It is illegal to distribute the script as part of a tutorial / script archive.
// Updated version available at: http://www.echoecho.com/toolfloatinglayer.htm
// This comment and the 4 lines above may not be removed from the code.
// this file is modified by Tim@TopCMM
//
var FC_floatX = 0;
var FC_floatY = 0;
var FC_halign = "center";
var FC_valign = "top";
var FC_layerwidth = 200;
var FC_layerheight = 114;
var FC_delayspeed = 5;
var FC_lastX = 0;
var FC_lastY = 0;
var FC_NS6 = false;
var FC_IE4 = (document.all != null);
if (!FC_IE4) {
    FC_NS6 = (document.getElementById != null);
}
var FC_NS4 = (document.layers != null);
var cc = 0;
function changeDivPosition(divName, x, y) {
    var thisX = Number(x);
    var thisY = Number(y);
    if ((FC_NS4) || (FC_NS6)) {
        thisX = window.pageXOffset + thisX;
        thisY = window.pageYOffset + thisY;
        if (FC_NS4) {
            document.layers[divName].pageX = thisX + "px";
            document.layers[divName].pageY = thisY + "px";
        }
        if (FC_NS6) {
            document.getElementById(divName).style.left = thisX + "px";
            document.getElementById(divName).style.top = thisY + "px";
        }
    } else if (FC_IE4) {
        var obj = document.getElementById(divName);
        var offsetx = document.documentElement.scrollLeft;
        if (document.body.scrollLeft > offsetx) {
            offsetx = document.body.scrollLeft;
        }
        var offsety = document.documentElement.scrollTop;
        if (document.body.scrollTop > offsety) {
            offsety = document.body.scrollTop;
        }
        obj.style.left = (offsetx + thisX) + "px";
        obj.style.top = (offsety + thisY) + "px";
    }
}
function changeStaticPosition(divName, x, y) {
    var thisX = Number(x);
    var thisY = Number(y);
    if ((FC_NS4) || (FC_NS6)) {
        if (FC_NS4) {
            document.layers[divName].pageX = thisX + "px";
            document.layers[divName].pageY = thisY + "px";
        }
        if (FC_NS6) {
            document.getElementById(divName).style.left = thisX + "px";
            document.getElementById(divName).style.top = thisY + "px";
        }
    } else if (FC_IE4) {
        document.getElementById(divName).style.left = thisX + "px";
        document.getElementById(divName).style.top = thisY + "px";
    }
}
function getElementX(divName) {
    var str = "";
    if ((FC_NS4) || (FC_NS6)) {
        var offsetX = window.pageXOffset;
        if (FC_NS4) {
            str = document.layers[divName].pageX - offsetX;
        }
        if (FC_NS6) {
            str = document.getElementById(divName).style.left;
        }
    } else if (FC_IE4) {
        var offsetx = document.documentElement.scrollLeft;
        if (document.body.scrollLeft > offsetx) {
            offsetx = document.body.scrollLeft;
        }
        var x = document.getElementById(divName).style.left;
        x = Number(x.substring(0, x.indexOf("px")));
        x = x - offsetx;
        str = x;
    }
    //
    str = String(str);
    if (str.indexOf("px") != -1) {
        var n = str.substring(0, str.indexOf("px"));
        n = Number(n);
        return n;
    } else {
        return Number(str);
    }
}
function getStaticX(divName) {
    var str = "";
    if ((FC_NS4) || (FC_NS6)) {
        if (FC_NS4) {
            str = document.layers[divName].pageX;
        }
        if (FC_NS6) {
            str = document.getElementById(divName).style.left;
        }
    } else if (FC_IE4) {
        str = document.getElementById(divName).style.left;
    }
    //
    str = String(str);
    if (str.indexOf("px") != -1) {
        var n = str.substring(0, str.indexOf("px"));
        n = Number(n);
        return n;
    } else {
        return Number(str);
    }
}
function getElementY(divName) {
    var str = "";
    if ((FC_NS4) || (FC_NS6)) {
        var offsetY = window.pageYOffset;
        if (FC_NS4) {
            str = document.layers[divName].pageY - offsetY;
        }
        if (FC_NS6) {
            str = document.getElementById(divName).style.top;
        }
    } else if (FC_IE4) {
        var offsety = document.documentElement.scrollTop;
        if (document.body.scrollTop > offsety) {
            offsety = document.body.scrollTop;
        }
        var y = document.getElementById(divName).style.top;
        y = Number(y.substring(0, y.indexOf("px")));
        y = y - offsety;
        str = y;
    }
    //
    str = String(str);
    if (str.indexOf("px") != -1) {
        var n = str.substring(0, str.indexOf("px"));
        n = Number(n);
        return n;
    } else {
        return Number(str);
    }
}
function getStaticY(divName) {
    var str = "";
    if ((FC_NS4) || (FC_NS6)) {
        if (FC_NS4) {
            str = document.layers[divName].pageY;
        }
        if (FC_NS6) {
            str = document.getElementById(divName).style.top;
        }
    } else if (FC_IE4) {
        str = document.getElementById(divName).style.top;
    }
    //
    str = String(str);
    if (str.indexOf("px") != -1) {
        var n = str.substring(0, str.indexOf("px"));
        n = Number(n);
        return n;
    } else {
        return Number(str);
    }
}