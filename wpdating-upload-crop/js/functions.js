jQuery(document).ready(function () {
    jQuery('#change-profile-pic').on('click', function (e) {
        jQuery('#profile_pic_modal').modal({show: true});
    });

    jQuery(".profile-image").mouseenter(function () {
        jQuery(".update_profile_text_div").css("display", "block");
    });

    jQuery(".profile-image").mouseleave(function () {
        jQuery(".update_profile_text_div").css("display", "none");
    });

    jQuery('#profile-pic').on('change', function () {
        jQuery("#preview-profile-pic").html('');
        jQuery("#preview-profile-pic").html('Uploading....');
        jQuery("#wpdating-profile-crop-image").ajaxForm(
            {
                target: '#preview-profile-pic',
                success: function () {
                    jQuery('img#photo').imgAreaSelect({
                        aspectRatio: '1:1',
                        x1: 0,
                        y1: 0,
                        x2: 180,
                        y2: 180,
                        onSelectEnd: getSizes,
                        handles: true,
                        parent: '.modal-content',
                        onSelectStart: function (img, selection) {
                            jQuery('#save_crop').text('Crop and Save');
                        }

                    });
                    jQuery('#image_name').val(jQuery('#photo').attr('file-name'));
                }
            }).submit();
    });

    jQuery('#save_crop').on('click', function (e) {
        e.preventDefault();
        params = {
            targetUrl: wp_image_area_select_object.path_change_pic,
            action: 'save',
            x_axis: jQuery('#hdn-x1-axis').val(),
            y_axis: jQuery('#hdn-y1-axis').val(),
            x2_axis: jQuery('#hdn-x2-axis').val(),
            y2_axis: jQuery('#hdn-y2-axis').val(),
            thumb_width: jQuery('#hdn-thumb-width').val(),
            thumb_height: jQuery('#hdn-thumb-height').val()
        };
        saveCropImage(params);
        jQuery(".imgareaselect-selection").parent().remove();

        jQuery(".imgareaselect-outer").remove();
        jQuery('#hdn-x1-axis').val('');
        jQuery('#hdn-y1-axis').val('');
        jQuery('#hdn-x2-axis').val('');
        jQuery('#hdn-y2-axis').val('');
        jQuery('#hdn-thumb-width').val('');
        jQuery('#hdn-thumb-height').val('');
        jQuery('#save_crop').text('Save');
    });


    jQuery('img#photo').imgAreaSelect({
        onSelectEnd: function (img, selection) {
            alert('width: ' + selection.width + '; height: ' + selection.height);
        }
    });

    function saveCropImage(params) {
        jQuery.ajax({
            url: params['targetUrl'],
            cache: false,
            dataType: "html",
            data: {
                action: params['action'],
                id: jQuery('#hdn-profile-id').val(),
                t: 'ajax',
                w1: params['thumb_width'],
                x1: params['x_axis'],
                h1: params['thumb_height'],
                y1: params['y_axis'],
                x2: params['x2_axis'],
                y2: params['y2_axis'],
                image_name: jQuery('#image_name').val(),
                nonce_field: jQuery('#_wpnonce_wpdating-profile-pic-change-form').val(),
            },
            type: 'Post',
            success: function (response) {
                jQuery('#profile_pic_modal').modal('hide');
                jQuery(".imgareaselect-border1,.imgareaselect-border2,.imgareaselect-border3,.imgareaselect-border4,.imgareaselect-border2,.imgareaselect-outer").css('display', 'none');
                jQuery("#profile_picture").attr('src', response);
                jQuery(".profile_picture_link").attr('href', response);
                jQuery('.update-message').show();
                jQuery('.error').show();
                jQuery('.error').text('Profile Picture Changed successfully').fadeOut(10000);
                jQuery('.update-message').fadeOut(10000);
                jQuery("#preview-profile-pic").html('');
                jQuery("#profile-pic").val();
                jQuery("#noFile").text("No image chosen...");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert('status Code:' + xhr.status + 'Error Message :' + thrownError);
            }
        });
    }

    /* Function to get images size */
    function getSizes(img, obj) {
        var x_axis = obj.x1;
        var x2_axis = obj.x2;
        var y_axis = obj.y1;
        var y2_axis = obj.y2;
        var thumb_width = obj.width;
        var thumb_height = obj.height;
        if (thumb_width > 0) {
            jQuery('#hdn-x1-axis').val(x_axis);
            jQuery('#hdn-y1-axis').val(y_axis);
            jQuery('#hdn-x2-axis').val(x2_axis);
            jQuery('#hdn-y2-axis').val(y2_axis);
            jQuery('#hdn-thumb-width').val(thumb_width);
            jQuery('#hdn-thumb-height').val(thumb_height);
        } else {
            alert("Please select portion..!");
        }
    }

    jQuery('#profile-pic').bind('change', function () {
        var filename = jQuery("#profile-pic").val();
        if (/^\s*jQuery/.test(filename)) {
            jQuery(".file-upload").removeClass('active');
            jQuery("#noFile").text("No image chosen...");
        }
        else {
            jQuery(".file-upload").addClass('active');
            jQuery("#noFile").text(filename.replace("C:\\fakepath\\", ""));
        }
    });

});

