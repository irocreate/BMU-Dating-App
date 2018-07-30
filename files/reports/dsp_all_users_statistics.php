<?php
    $users = '';
    //$request_url = get_bloginfo('url') . "/wp-admin/admin.php?page=dsp-admin-sub-page4";
    $msg = '';
	include_once(dirname(__FILE__) . '/../classes/class-user-statistics.php');
    $userInfo = new UserStats_class();
    $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
	$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
	$userByDate = '';
    $countryList = array();
    $countryWiseUsers = array();
    $totalMaleUsers = '';
    $totalFemaleUsers = '';
    $totalUserByCountry = $userInfo->getAllUsersByCountry();
    $totalUserByGender = $userInfo->countAllUsersByGender();
    $totalPremiumUsers = $userInfo->countAllPremiumUsers();
    foreach ($totalUserByCountry as $k => $v) {
        foreach ($v as $key => $value) {
            if($key == 'country'){
             array_push($countryList,$value);
           }else if($key == 'total'){
             array_push($countryWiseUsers,$value);
           }
       }
    }
 ?>
<div id="general" class="postbox report-all-user-statistics">
    <h3 class="hndle"><span><?php echo language_code('DSP_USER_STATISTICS') ?></span></h3>
    <div style="margin:20px">
        <table class="dsp_thumbnails1" border="0" width="100%">
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="2" style="padding-top:20x" class="user-statistics-first-column-table">
                        <tr>
                            <td style="font-weight:bold;"><?php echo language_code('DSP_MALE');?></td>
                            <td align="left" style="width:20px;color:#6D6D6D; font-weight:bold;"><?php echo $totalUserByGender[0]->count; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;"><?php echo language_code('DSP_FEMALE');?></td>
                            <td align="left" style="width:20px;color:#6D6D6D; font-weight:bold;"><?php  $femaleUsers = isset($totalUserByGender) && count($totalUserByGender) > 1   ? $totalUserByGender[1]->count : 0; echo $femaleUsers; ?></td>
                        </tr>

                        <tr>
                        	<td style="font-weight:bold;"><?php echo language_code('DSP_PREMIUM_MEMBER');?></td>
                            <td style="color:#6D6D6D; font-weight:bold;"><?php echo $totalPremiumUsers[0]->total; ?></td>
                            
                        </tr>

                        <tr>
                        	<td style="font-weight:bold;" class="report-users-by-country"><?php echo language_code('DSP_USERS_BY_COUNTRY');?></td>
                            <td>
                                <table width="100%" class="report-users-by-country-table">
                                    <tr>
                                        <?php foreach ($countryList as $key => $value):  ?>
                                            <th><?php echo $value; ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                     <tr>
                                        <?php foreach ($countryWiseUsers as $key => $value): ?>
                                            <td><?php echo $value; ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                </table>
                            </td>
                       </tr> 
                                              
                    </table>
                </td>


            </tr>

        </table>
    </div>
</div>
<br />
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>

