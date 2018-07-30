/* Copyright (c) 2012 Mobile Developer Solutions. All rights reserved.
 * This software is available under the MIT License:
 * The MIT License
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software 
 * and associated documentation files (the "Software"), to deal in the Software without restriction, 
 * including without limitation the rights to use, copy, modify, merge, publish, distribute, 
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software 
 * is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies 
 * or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR 
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE 
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, 
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

$(document).on('pagebeforeshow', '#page_register', function()
{
    var siteName = localStorage.getItem("siteName");

    siteUrl = siteName + "/wp-content/plugins/dsp_dating/m1/dsp_register.php";

    $.ajax({
        type: "GET",
        url: siteUrl,
        cache: false,
        dataType: "text",
        success: onSuccess,
        timeout: 5000,
        error: onError

    });

    function onSuccess(data, status)
    {
        dataT = $.trim(data);
        $('#page_register_div').empty();

        $('#page_register_div').append(dataT);
        $('#page_register').trigger('create');
    }

    function onError(jqXHR, status, errorThrown)
    {
        alert('Network error has occurred please try again!');
        // handle an error
    }



});





$(document).on('click', '#register', function()
{
    //  alert('click');

    //catch the form's submit event
    if ($('#username').val().length > 0 && $('#email').val().length > 0)
    {
        // action is functionality we want to call and outputJSON is our data
        var formData = $("#register-user").serialize();

        var siteName = localStorage.getItem("siteName");

        siteUrl = siteName + "/wp-content/plugins/dsp_dating/m1/getRegData.php";


        $.ajax({
            url: siteUrl,
            data: formData, // Convert a form to a JSON string representation
            type: 'post',
            async: true,
            dataType: "jsonp",
            jsonpCallback: 'successCallback',
            beforeSend: function() {
                $.mobile.showPageLoadingMsg(true);
            },
            complete: function() {
                $.mobile.hidePageLoadingMsg();
            },
            success: function(result)
            {
                $('#reg_result_register').empty();
                //   alert('succ==');
                $.each(result, function(i, row)
                {
                    $('#reg_result_register').append('<p>' + row.section_title + '</p>');
                });
            },
            error: function(x, t, m) {
                alert('Network error has occurred please try again!');
            }

        });
    }
    else
    {
        alert('Please fill all necessary fields');
    }

});

$(document).on('click', '#show_reg', function()
{

    $.mobile.changePage("register.html");
});

function reloadCaptcha()
{

    document.getElementById('captcha').src = document.getElementById('captcha').src + '?' + new Date();
}

////////////////////////////////////////////////////////////////////////////




$(document).on('click', '#submit', function()
{
    if ($('#loginUsername').val().length > 0 && $('#password').val().length > 0)
    {
        var userName = $('#loginUsername').val();
        var siteName = localStorage.getItem("siteName");

        siteUrl = siteName + "/wp-content/plugins/dsp_dating/m1/dsp_login.php";

        var formData = $("#login_form").serialize();

        $.ajax({
            url: siteUrl,
            data: formData, // Convert a form to a JSON string representation
            type: 'post',
            async: true,
            dataType: "jsonp",
            jsonpCallback: 'successCallback',
            beforeSend: function() {
                $.mobile.showPageLoadingMsg(true);
            },
            complete: function() {
                $.mobile.hidePageLoadingMsg();
            },
            success: function(result)
            {
                $.each(result, function(i, row)
                {
                    if (row.section_title == 'valid')
                    {
                        var uid = row.section_id;

                        localStorage.setItem("userName", userName);
                        localStorage.setItem("userId", uid);

                        $.mobile.changePage("home.html");
                    }
                    else
                    {
                        $('#reg_result').empty();
                        $('#reg_result').append('<p>' + row.section_title + '</p>');
                    }


                });


            },
            error: function(x, t, m) {
                alert('Network error has occurred please try again!');
            }
        });

    }
    else
    {
        alert('Please fill all necessary fields');
    }


});

$(document).on('pageinit', '#page-login', function()
{
    var siteName = localStorage.getItem("siteName");

    siteUrl = siteName + "/wp-content/plugins/dsp_dating/m1/dspGetLogo.php";

    $.ajax({
        type: "GET",
        url: siteUrl,
        cache: false,
        dataType: "text",
        success: onSuccessLogo,
        timeout: 5000,
        error: onErrorLogo

    });

    function onSuccessLogo(data, status)
    {
        $("#siteLogo").attr("src", data);
    }

    function onErrorLogo(jqXHR, status, errorThrown)
    {

    }

    /* siteUrl=siteName+"/wp-content/plugins/dsp_dating/m1/dsplogin.php";
     $.ajax({
     type: "GET",
     url: siteUrl,
     cache: false,
     dataType: "text",
     success: function (data, status)
     {
     dataT = $.trim(data);
     $('#page_register_div').empty();
     
     $('#page_register_div').append(dataT);
     $('#page_register').trigger('create');
     },
     timeout: 5000,
     error: function(jqXHR, status,errorThrown)
     {
     alert('Network error has occurred please try again!');
     // handle an error
     }  
     
     });
     */





});

$(document).on('pagebeforeshow', '#page-login', function()
{

    var userName = localStorage.getItem("userName", userName);

    if (userName)
    {
        $.mobile.changePage("home.html");
    }



});

$(document).on('click', '#btn_logout', function()
{
    localStorage.setItem("userName", "");
    localStorage.setItem("userId", "");

    $.mobile.changePage("index.html");
});

$(document).on('pagebeforeshow', '#online_page', function()
{

    var siteName = localStorage.getItem("siteName");

    siteUrl = siteName + "/wp-content/plugins/dsp_dating/m1/dspGetOnline.php";

    var userId = localStorage.getItem("userId");
    var userList = {'user_id': userId};

    $.ajax({
        type: "GET",
        url: siteUrl,
        cache: false,
        dataType: "text",
        data: userList,
        beforeSend: function() {
            $.mobile.showPageLoadingMsg(true);
        },
        complete: function() {
            $.mobile.hidePageLoadingMsg();
        },
        success: onSuccessOnline,
        timeout: 5000,
        error: onErrorOnline

    });

    function onSuccessOnline(data, status)
    {
        // alert('succ');
        $('#online').empty();
        $('#online').append(data);
    }

    function onErrorOnline(jqXHR, status, errorThrown)
    {
        alert('Network error has occurred please try again!');
    }

});

$(document).on('pageinit', '#mainPage', function()
{
    if (Modernizr.localstorage)
    {
        var item = localStorage.getItem("siteName");
        if (item != null)
        {

            $.mobile.changePage("index.html", {
                transition: "pop",
                reverse: true,
                changeHash: false
            });
        }

    }
    else
    {
        alert("Unfortunately your browser doesn't support local storage");
    }
});

$(document).on('click', '#submit_site', function()
{
    if ($('#sitename').val().length > 0)
    {

        var site = $('#sitename').val();

        var formData = $("#check_site").serialize();

        $.ajax({
            type: "GET",
            url: "http://www.dsdev.biz/wp-content/plugins/mobile_app/checkValidSite.php",
            data: formData, // Convert a form to a JSON string representation
            dataType: "text",
            beforeSend: function() {
                $.mobile.showPageLoadingMsg(true);
            },
            complete: function() {
                $.mobile.hidePageLoadingMsg();
            },
            success: function(data, status)
            {
                //   alert('succ'+data);
                if (data == "false")
                {
                    alert('Sorry this is not a valid site!');

                }
                else
                {
                    //   alert(data);
                    localStorage.setItem("siteName", data);
                    localStorage.setItem("site", site);

                    $.mobile.changePage("index.html", {
                        transition: "pop",
                        reverse: false,
                        changeHash: false
                    });
                }
            },
            timeout: 5000,
            error: function(jqXHR, status, errorThrown)
            {
                alert('Network error has occurred please try again!');
            }

        });
    }
    else
    {
        alert("Please enter site name.");
    }

});

$(document).on('pagebeforeshow', '#home_page', function()
{
    var site_name = localStorage.getItem("site");
    $('#site_name').empty();
    $('#site_name').append(site_name);
});

/* function for online page */
function addFriend(frndId)
{
    var userId = localStorage.getItem("userId");

    var userList = {'user_id': userId, 'frnd_userid': frndId};

    var siteName = localStorage.getItem("siteName");
    siteUrl = siteName + "/wp-content/plugins/dsp_dating/m1/dsp_add_frnd.php";

    $.ajax({
        type: "GET",
        url: siteUrl,
        data: userList,
        dataType: "text",
        beforeSend: function() {
            $.mobile.showPageLoadingMsg(true);
        },
        complete: function() {
            $.mobile.hidePageLoadingMsg();
        },
        success: function(data, status)
        {
            alert(data);
        },
        timeout: 5000,
        error: function(jqXHR, status, errorThrown)
        {
            alert('Network error has occurred please try again!');
        }

    });
}

function addFavourite(frndId)
{
    var userId = localStorage.getItem("userId");

    var userList = {'user_id': userId, 'fav_userid': frndId};

    var siteName = localStorage.getItem("siteName");
    siteUrl = siteName + "/wp-content/plugins/dsp_dating/m1/dsp_add_favourites.php";

    $.ajax({
        type: "GET",
        url: siteUrl,
        data: userList, // Convert a form to a JSON string representation
        dataType: "text",
        beforeSend: function() {
            $.mobile.showPageLoadingMsg(true);
        },
        complete: function() {
            $.mobile.hidePageLoadingMsg();
        },
        success: function(data, status)
        {
            alert(data);
        },
        timeout: 5000,
        error: function(jqXHR, status, errorThrown)
        {
            alert('Network error has occurred please try again!');
        }

    });
}

function sendWink(frndId)
{
    var userId = localStorage.getItem("userId");

    var userList = {'user_id': userId, 'receiver_id': frndId, 'pagetitle': 'send_wink_msg'};

    var siteName = localStorage.getItem("siteName");
    siteUrl = siteName + "/wp-content/plugins/dsp_dating/m1/dsp_header.php";

    $.ajax({
        type: "GET",
        url: siteUrl,
        data: userList, // Convert a form to a JSON string representation
        dataType: "text",
        beforeSend: function() {
            $.mobile.showPageLoadingMsg(true);
        },
        complete: function() {
            $.mobile.hidePageLoadingMsg();
        },
        success: function(data, status)
        {
            $(document).on('pagebeforeshow', '#wink', function()
            {
                $('#wink_content').empty();
                $('#wink_content').append(data);
                $('#wink_content').trigger('create');
            });

            $.mobile.changePage("dsp_wink.html");
        },
        timeout: 5000,
        error: function(jqXHR, status, errorThrown)
        {
            alert('Network error has occurred please try again!');
        }

    });
}

$(document).on('click', '#send_flirt', function()
{

    var formData = $("#sendwinkfrm").serialize();
    $.ajax({
        type: "GET",
        url: siteUrl,
        data: formData, // Convert a form to a JSON string representation
        dataType: "text",
        beforeSend: function() {
            $.mobile.showPageLoadingMsg(true);
        },
        complete: function() {
            $.mobile.hidePageLoadingMsg();
        },
        success: function(data)
        {
            // alert('succ'+data);
            $('#msg').empty();

            $('#msg').append('<p>' + data + '</p>');

        },
        timeout: 5000,
        error: function(jqXHR, status, errorThrown)
        {
            alert('Network error has occurred please try again!');
        }
    });

});

//$(document).on('click', '#login_filter',getOnlinePage);

function getOnlinePage(page)
{

    if (page == 0)
    {
        var formData = $("#frm_online").serialize();
    }
    else
    {
        alert('page=' + page);
        var userId = localStorage.getItem("userId");
        var formData = {'user_id': userId, 'page1': page};
    }

    var siteName = localStorage.getItem("siteName");

    siteUrl = siteName + "/wp-content/plugins/dsp_dating/m1/dspGetOnline.php";

    $.ajax({
        type: "GET",
        url: siteUrl,
        cache: false,
        dataType: "text",
        data: formData,
        beforeSend: function() {
            $.mobile.showPageLoadingMsg(true);
        },
        complete: function() {
            $.mobile.hidePageLoadingMsg();
        },
        success: onSuccessOnline,
        timeout: 5000,
        error: onErrorOnline

    });

    function onSuccessOnline(data, status)
    {
        // alert('succ');
        $('#online').empty();
        $('#online').append(data);
    }

    function onErrorOnline(jqXHR, status, errorThrown)
    {
        alert('Network error has occurred please try again!');
    }

}

/* end of  function for online page */

