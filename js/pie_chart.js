/**
 * Script to display piechart -using google api
 Copyright (C) www.wpdating.com - All Rights Reserved!
 Author - MyAllenMedia, LLC
 WordPress Dating Plugin
 contact@wpdating.com
 */
var male = "";
    
jQuery(document).ready(function () {
    male = jQuery('div #chart_div').data('male');
    female = jQuery('div #chart_div').data('female');
    couples = jQuery('div #chart_div').data('couples');
    title = jQuery('div #chart_div').data('title');
});


google.load("visualization", "1", {packages: ["corechart"]});
google.setOnLoadCallback(drawChart);


function drawChart(elem) {
    var elem = document.getElementById('chart_div');
    if(elem == null) {
        return false;
    }
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Gender');
    data.addColumn('number', 'Member');
    data.addRows([
        ['Male', male],
        ['Female', female],
        ['Couple', couples]
    ]);

    var options = {
        legend: 'none',
        'title': title,
        is3D: true
    };

    var chart = new google.visualization.PieChart(elem);
    chart.draw(data, options);
    
}
