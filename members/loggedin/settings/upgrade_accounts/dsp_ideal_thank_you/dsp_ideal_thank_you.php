<?php 
$discount_code = isset($_SESSION['code'])? $_SESSION['code']: '';
if( isset($discount_code) && !empty($discount_code)){
    dsp_update_discount_coupan_used($discount_code);
    add_user_meta(get_current_user_id(),'discount_code',$discount_code);
}
if(isset($_SESSION['code'])){
	unset($_SESSION['code']);
}
?>
<div class="box-border">
    <div class="box-pedding">
        <div align="center" style="color:#FF0000;"><b><?php echo language_code('DSP_THANKYOU_FOR_UR_PAYMENT'); ?></b></div>
    </div>
</div>