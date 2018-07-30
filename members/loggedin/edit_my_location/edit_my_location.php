<?php
 
 $lattitue = isset($_GET['lat']) ? $_GET['lat'] : '';
 $longitue = isset($_GET['lng']) ? $_GET['lng'] : '';
 $saved = false;
 if(isset($_POST['save'])){
 	$userLat = isset($_POST['lat']) ? $_POST['lat'] : '';
 	$userLng = isset($_POST['lng']) ? $_POST['lng'] : '';
 	if(!empty($userLat) && !empty($userLng)){
 		$position = array(
 			             'lat' => $userLat,
 						 'lng' => $userLng
 						);
 		$saved = apply_filters('dsp_savePosition',$position);
 	}
 }


if(!empty($lattitue) && !empty($longitue))
 {
 ?>
 <form action="" method="POST" class="edit_my_location_form">
   <input type="hidden" value="<?php echo $lattitue;?>" id="lat" name="lat">
   <input type="hidden" value="<?php echo $longitue;?>" id="lng" name="lng">
   <input name="submit" class="dsp_submit_button dspdp-btn dsp-save-button"type="submit" value="Save my Location"  id="edit_my_location" />
   <input type="hidden" name="save" value="submit" />
</form>
   <div id="map_wrapper" style="height: 500px; width:500; margin-left:-1px;" class="map_wrapper_form">
        <div id="map_canvas" class="mapping" style="width: 100%;height: 100%;"></div>
    </div>
 <?php

 }else{
 	echo "lattitue & longitude not found";
 }
 ?>
 </div>