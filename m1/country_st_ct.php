<?php include("../../../../wp-config.php"); ?>
<script type="text/javascript">
/*
alert(typeof jQuery);
    var jQ = jQuery.noConflict();
    jQuery(document).ready(function() {
    	alert("inside");
        jQuery("#cmbCountry_id").change(function() {
           // alert('mmm');	
            var country = jQuery(this).val();
            country = country.replace(/ /g, '%20');
            jQuery("#state_change").load("?php echo plugins_url('dsp_dating/m/get_state_city.php'); ??country=" + country);
            jQuery("#city_change").load("?php echo plugins_url('dsp_dating/m/get_city.php'); ??state=0&country=" + country);
        });

        //dsp("#cmbState_id").on("change",function(){
        jQuery(document).on('change', '#cmbState_id', function() {

            var state = jQuery(this).val();
            var country = jQuery("#cmbCountry_id").val();
            country = country.replace(/ /g, '%20');
            state = state.replace(/ /g, '%20');

            jQuery("#city_change").load("?php echo plugins_url('dsp_dating/m/get_city.php') ??state=" + state + "&country=" + country);
        });
    });
*/

//alert("i am in here");
var countrySel = document.getElementById("cmbCountry_id");
var state_change = document.getElementById("state_change");
var city_change = document.getElementById("city_change");
var stateSel = document.getElementById("cmbState_id");

countrySel.onchange = function() {
	var selectedCountry = this.value;
	
	selectedCountry = selectedCountry.replace(/ /g, '%20');

	var get_state_city_url = "<?php echo plugins_url('dsp_dating/m1/get_state_city.php'); ?>?country=" + selectedCountry;
	make_request(get_state_city_url, state_change);

	var get_city_url = "<?php echo plugins_url('dsp_dating/m1/get_city.php'); ?>?state=0&country=" + selectedCountry;
	make_request(get_city_url, city_change);
};

document.getElementById("state_change").addEventListener( 'change', function(e) {
	var targ = e.target;
	if( targ.id == "cmbState_id" ) {
		var selectedState = targ.value;
		selectedState = selectedState.replace(/ /g, '%20');
		var selectedStateCountry = countrySel.options[countrySel.selectedIndex].value;
		selectedStateCountry = selectedStateCountry.replace(/ /g, '%20');

		var get_ct_url = "<?php echo plugins_url('dsp_dating/m1/get_city.php'); ?>?state=" + selectedState + "&country=" + selectedStateCountry;
		make_request(get_ct_url, city_change);
	}
});


function make_request(url, ele) {
	var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
        	//alert("Response:" + xmlhttp.responseText);
            ele.innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
}
</script>