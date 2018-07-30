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
                $('#reg_result').empty();
                //   alert('succ==');
                $.each(result, function(i, row)
                {
                    $('#reg_result').append('<p>' + row.section_title + '</p>');
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

function addFriend(frndId)
{
    var userId = localStorage.getItem("UserId");
    alert('frnd id=' + frndId + userId);
}
