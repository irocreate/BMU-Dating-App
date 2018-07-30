<?php
    $users = '';
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
    if(isset($_GET['search'])){
        $filters = array(
							'startDate' => $startDate,
							'endDate' => $endDate,
						);
		$totalUserByCountry = $userInfo->getAllUsersByCountry($filters);
        $totalUserByGender = $userInfo->countAllUsersByGender($filters);
        $totalPremiumUsers = $userInfo->countAllPremiumUsers($filters);
        
        foreach ($totalUserByCountry as $k => $v) {
            foreach ($v as $key => $value) {
                if($key == 'country'){
                 array_push($countryList,$value);
               }else if($key == 'total'){
                 array_push($countryWiseUsers,$value);
               }
           }
        }
	}else{
		if(isset($_GET['search'])){
				$msg = '<p><?php echo language_code(\'DSP_DATEFIELD_EMPTY\');?></p>';
		}
	}


?>

<form method ="get" name="searchByDate" action="" class="report-user-statistics">
	<input name="page" type="hidden" value="dsp-admin-sub-page4"/>
	<input name="pid" type="hidden" value="Userstats"/>
	<div class="dsp_membership_col2"><input name="startDate"  placeholder = "<?php echo language_code('DSP_START_DATE'); ?>" class="datepicker-control" id ="startDate" type="text" value="<?php if(isset($startDate)){echo $startDate;}else{echo language_code('DSP_START_DATE');}?>" /></div>
	<div class="dsp_membership_col2"><input name="endDate" placeholder = "<?php echo language_code('DSP_END_DATE'); ?>" class="datepicker-control" id ="endDate" type="text" value="<?php if(isset($endDate)){echo $endDate;}else{echo language_code('DSP_END_DATE');}?>" /></div>
    <div class="dsp_membership_col3"><input type="submit" name="search" class="button"  value="Search"/></div>
   <div class="dsp_clr"></div>
</form>

<?php if(!empty($totalUserByCountry) || !empty($totalUserByGender) || !empty($totalPremiumUsers)):?>
<div id="general" class="postbox report-user-statistics-postbox">
    <h3 class="hndle"><span><?php echo language_code('DSP_USER_STATISTICS') ?></span></h3>
    <div style="margin:20px">
        <table class="dsp_thumbnails1" border="0" width="100%">
            <tr>
                <td>
                    <table width="500" border="0" cellspacing="0" cellpadding="2" style="padding-top:20x">
                        <tr>
                            <td style="font-weight:bold;"><?php echo language_code('DSP_MALE');?></td>
                            <td align="left" style="width:20px;color:#6D6D6D; font-weight:bold;"><?php echo $totalUserByGender[0]->count; ?></td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold;"><?php echo language_code('DSP_FEMALE');?></td>
                            <td align="left" style="width:20px;color:#6D6D6D; font-weight:bold;"><?php  $femaleUsers = count($totalUserByGender[1]->count) > 0  ? $totalUserByGender[1]->count : count($totalUserByGender[1]->count); echo $femaleUsers; ?></td>
                        </tr>

                        <tr>
                        	<td style="font-weight:bold;"><?php echo language_code('DSP_PREMIUM_MEMBER');?></td>
                            <td style="color:#6D6D6D; font-weight:bold;"><?php echo $totalPremiumUsers[0]->total; ?></td>
                            
                        </tr>

                        <tr>
                        	<td style="font-weight:bold;"><?php echo language_code('DSP_USERS_BY_COUNTRY');?></td>
                            <td>
                                <table class="report-users-by-country-table" >
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
<?php endif; ?>
