jQuery(document).ready(function(){

    jQuery(".inline_report_comment").click(function(){
        var value = jQuery(this).data("id");
        var titles = jQuery(this).data("title");
        jQuery('#comments-id').val(value);
        jQuery(".inline_report_comment").colorbox({title: titles });
    });


    jQuery(".inline_report_comment").colorbox({inline:true, width:"40%"});
});

// To report member JQuery start

jQuery(document).ready(function(){

    jQuery(".report_mem").click(function(){
        var value = jQuery(this).data("id");
        var titles = jQuery(this).data("title");
        jQuery('#report-id').val(value);
        jQuery(".report_mem").colorbox({title: titles });
    });


    jQuery(".report_mem").colorbox({inline:true, width:"40%"});
});

// Report member JQuery Ends