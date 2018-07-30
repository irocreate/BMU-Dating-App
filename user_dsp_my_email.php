<?php

/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
$exist_profile_details = $wpdb->get_row("SELECT * FROM $dsp_user_profiles WHERE user_id = '$current_user->ID'");
$profile_user_id = $exist_profile_details->user_id;
?>
<?php /* ?><div class="dsp_box-out">
  <div class="dsp_box-in">
  <table cellpadding="0" cellspacing="0" width="100%" border="0">
  <tr>
  <td width="23%" align="center" valign="top">
  <a href="<?php echo display_members_photo($current_user->ID,$pluginpath); ?>" rel="lightbox[image1]"> <img src="<?php echo display_members_photo($current_user->ID,$pluginpath); ?>" width="100px" class="img"></a>
  </td>

  <td width="50%" valign="top">
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
  <td colspan="2" height="15px"></td>
  </tr>

  <tr>
  <td height="20px" style="padding-left:5px;"><?php echo DSP_AGE?></td>
  <td><?php echo GetAge($exist_profile_details->age); ?></td>
  </tr>

  <tr>
  <td height="20px" style="padding-left:5px;"><?php echo DSP_GENDER?></td>
  <td>
  <?php if($exist_profile_details->gender =='F') {?>
  <?php echo DSP_WOMAN?>
  <?php } else { ?>
  <?php echo DSP_MAN?>
  <?php } ?>
  </td>
  </tr>

  <tr>
  <td height="20px" style="padding-left:5px;"><?php echo DSP_SEEKING_A?></td>
  <td>
  <?php if($exist_profile_details->seeking =='M') {?>
  <?php echo DSP_MAN?>
  <?php } else { ?>
  <?php echo DSP_WOMAN?>
  <?php } ?>
  </td>
  </tr>

  <tr>
  <td height="20px" style="padding-left:5px;"><?php echo DSP_USER_LOCATION?></td>
  <?php
  $country = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$exist_profile_details->country_id");
  $countryname= $country->name;
  $state_query = $wpdb->get_row("SELECT * FROM $dsp_state_table where state_id=$exist_profile_details->state_id");
  $state_name=$state_query->name;
  $city_query = $wpdb->get_row("SELECT * FROM $dsp_city_table where city_id=$exist_profile_details->city_id");
  $cityname=$city_query->name;

  if(!empty($cityname)) {
  $jointcity=",&nbsp;";
  } else {
  $jointcity="";
  }

  if(!empty($state_name)) {
  $jointstate=",&nbsp;<br>";
  } else {
  $jointstate="";
  }
  //$cityname=$exist_profile_details->city;
  //$location=$countryname.",&nbsp;".$state_name.",&nbsp;<br>".$cityname;
  $location=$cityname.$jointcity.$state_name.$jointstate.$countryname;
  ?>
  <td><?php
  echo $location;
  //$country = $wpdb->get_row("SELECT * FROM $dsp_country_table where country_id=$exist_profile_details->country_id");
  //  echo $country->name;
  ?></td>
  </tr>
  </table>
  </td>
  <td width="27%" valign="top">
  <table border="0" cellspacing="0" cellpadding="0" width="100%">

  <tr>
  <td colspan="2" style="padding-left:5px;"><strong><?php echo DSP_USER_QUICK_STATS?></strong></td>
  </tr>

  <tr>
  <td colspan="2" height="4px"></td>
  </tr>

  <tr>
  <td width="65%" style="padding-left:3px;"><?php echo DSP_USER_NEW_MESSAGES?></td>
  <td width="35%" style="padding-left:2px;"><?php echo $count_inbox_messages?></td>
  </tr>

  <tr>
  <td style="padding-left:3px;"><?php echo DSP_USER_NEW_FRIENDS?></td>
  <td style="padding-left:2px;"><?php echo $count_friends_request?></td>
  </tr>

  <tr>
  <td width="65%" style="padding-left:3px;"><?php echo DSP_MY_FRIENDS?>:</td>
  <td width="35%" style="padding-left:2px;"><?php echo $count_user_total_friends?></td>
  </tr>

  <tr>
  <td width="65%" style="padding-left:3px;"><?php echo DSP_MY_MESSAGES?>:</td>
  <td width="35%" style="padding-left:2px;"><?php echo $count_user_total_messages?></td>
  </tr>

  </table>
  </td>
  </tr>
  </table>
  </div>
  </div><?php */ ?>
<?php

//---------------------------------MESSAGES,NEW MESSAGES,FRIENDS,WINKS,ALERTS ------------------------------------//
//-------------------------------------------------------------------------------------------------------------- //
// -------------Check condition if user login or not .if user login then that form will be display. ------------//  
if (is_user_logged_in()) {
    // echo "<br>";
     
    include_once( WP_DSP_ABSPATH .  "headers/email_template_header.php" );  // INCLUDE EMAIL TEMPLATES HEADER
}
//------------------------------------------------------------------------------------------------------------//
// --------------------------------------------------------------------------------------------------------- //
?>