<?php  
//  ############################  UPDATE DISCOUNT DETAILS ############################### //
/*
  Copyright (C) www.wpdating.com - All Rights Reserved!
  Author - www.wpdating.com
  WordPress Dating Plugin
  contact@wpdating.com
 */
global $wpdb;
$dsp_discount_codes_table  = $wpdb->prefix.DSP_DISCOUNT_CODES_TABLE;
$goback = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$dsp_action = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$name = isset($_REQUEST['discount_name']) ? $_REQUEST['discount_name'] : '';
$description = isset($_REQUEST['discount_description']) ? $_REQUEST['discount_description'] : '';
$code = isset($_REQUEST['discount_code']) ? $_REQUEST['discount_code'] : '';
$amount = isset($_REQUEST['discount_amount']) ? $_REQUEST['discount_amount'] : '';
$type = isset($_REQUEST['discount_type']) ? $_REQUEST['discount_type'] : '';
$status = isset($_REQUEST['discount_status']) ? $_REQUEST['discount_status'] : '';
$uses = isset($_REQUEST['discount_uses']) ? $_REQUEST['discount_uses'] : '';

if (isset($_POST['submit'])) {
    $dsp_action = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
    switch ($dsp_action) {
        case 'add':
            if (!empty($name) && !empty($description) && !empty($code) && !empty($amount)  && !empty($status)) {
                $errors = 0;
                $values = array(
                                'name'=>$name,
                                'description'=>$description,
                                'code'=>$code,
                                'amount'=>$amount,
                                'type'=>$type,
                                'status' => $status,
                                );
                $format = array('%s','%s','%s','%f','%s','%d');
                $wpdb->insert($dsp_discount_codes_table ,$values,$format);
            } // END  if ( !empty($dsp_mem_name) && !empty($dsp_mem_price) && !empty($dsp_mem_days))
            if($wpdb->insert_id){
                echo language_code('DSP_NEW_DISCOUNT_CODE_ADDED');
                echo '<a href="admin.php?page=dsp-admin-sub-page5&pid=dsp_discount_codes">'.language_code('DSP_BACK').'</a>';
            }
            exit();
            break;

        case 'update':
            if (!empty($name) && !empty($description) && !empty($code) && !empty($amount)) {
              $discount_id = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : '';
              $wpdb->update($dsp_discount_codes_table, 
                                array(
                                    'name' => $name,
                                    'description' => $description,
                                    'code' => $code,
                                    'amount' => $amount,
                                    'type' => $type, 
                                    'status' => $status),
                                array('id' => $discount_id),
                                array('%s', '%s', '%s', '%d','%s','%d'),
                                array('%d')
                        );
             
            } // END if ( !empty($dsp_mem_name) && !empty($dsp_mem_price) && !empty($dsp_mem_days))
            echo language_code('DSP_NEW_DISCOUNT_CODE_UPDATED');
            echo '<a href="admin.php?page=dsp-admin-sub-page5&pid=dsp_discount_codes">'.language_code('DSP_BACK').'</a>';
            exit();
            break;

        
    } // CLOSE SWITCH CASE \0
}
if (isset($_GET['Action']) && $_GET['Action'] == "deactivate") {
    $discount_id = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : '';
     $wpdb->update($dsp_discount_codes_table, 
                                array('status' => 0),
                                array('id' => $discount_id),
                                array('%d'),
                                array('%d')
                    );
   // echo 'Discount code deactivated !!!!  <a href="'.$goback.'">Click here to go back</a>';
   
} // END if($_GET['Action']=="Del")

//------------------------start delete membership plan------------------------------------- //		
if (isset($_GET['Action']) && $_GET['Action'] == "Del") {
    $discount_id = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : '';
    $wpdb->query("DELETE FROM $dsp_discount_codes_table WHERE id = '$discount_id'");
} // END if($_GET['Action']=="Del")
//------------------------ end delete membership plan------------------------------------- //
//  ########################################################################################################## //
?>
<style>
    .dsp_membership_wrap{
        margin-left:2px;
        padding:15px;
        width:700px;
        display:block;
    }
    .dsp_membership_col1 {
        width:130px;
        padding-left:6px;
        float:left;
        display:block;
        height:25px;
    }
    .dsp_membership_col2 {
        width:100px;
        height:20px;
        display:block;
        float:left;
    }
    .dsp_membership_col3 {
        width:200px;
        height:20px;
        display:block;
        float:left;
        text-align:center;
    }
    .dsp_membership_col4 {
        height:20px;
        display:block;
        float:left;
    }
    .dsp_membership_col6 {
        height:20px;
        display:block;
        float:left;
        width:80%;
    }
    .dsp_membership_col5 {
        width:130px;
        height:80px;
        display:block;
        float:left;
    }
    .dsp_membership_active_col {
        width:20px;
        height:20px;
        text-align:right;
        float:left;
        display:block;
    }
    .dsp_membership_wrap.dsp_discount_code_wrap{width: 100%;}
    .dsp_discount_code_wrap .dsp_membership_col1{width:120px; text-align: center;}
   .dsp_discount_code_wrap .dsp_membership_col3{width: 120px;}
   .discount_form{
    margin-top:20px;
   }
</style>
<div id="general" class="postbox" >
        <form name="updatedisplay_statusfrm" action="" method="post">
          <table class="wp-list-table widefat fixed users">
                <thead>    
                    <tr>     
                        <th><strong>Id</strong></th>
                        <th><strong><?php _e(language_code('DSP_NAME')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_DISCOUNT_DESCRIPTION')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_DISCOUNT_CODE')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_DISCOUNT_TYPE')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_DISCOUNT_AMOUNT')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_STATUS')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_DISCOUNT_USES')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_ACTION')); ?></strong></th>
                    </tr>
               </thead> 
            <?php
            $myrows = $wpdb->get_results("SELECT * FROM $dsp_discount_codes_table Order by name");
            ?>
            <tbody>
            <?php if(count($myrows) > 0){ 
                        foreach ($myrows as $discount_code) {
                            $id = $discount_code->id;
                            $name = $discount_code->name;
                            $description = $discount_code->description;
                            $code = $discount_code->code;
                            $amount = $discount_code->amount;
                            $type = $discount_code->type;
                            $status = ($discount_code->status == 0) ? language_code('DSP_INACTIVE') :  language_code('DSP_ACTIVE');
                            $uses = $discount_code->uses;

                    ?>
                            <tr>
                                <td><?php _e($id) ?></td>
                                <td><?php _e($name) ?></td>
                                <td ><?php _e($description) ?></td>
                                <td ><?php _e($code) ?></td>
                                <td ><?php _e($amount) ?></td>
                                <td ><?php _e($type) ?></td>
                                <td ><?php _e($status); ?></td>
                                <td><?php _e($uses) ?></td>
                                <td>
                                        <span onclick="update_discount_codes(<?php echo $id ?>);" class="span_pointer"><?php _e(language_code('DSP_EDIT')); ?></span>/
                                        <span onclick="delete_discount_codes(<?php echo $id ?>);" class="span_pointer"><?php _e(language_code('DSP_DELETE')); ?></span>
                                        <span onclick="deactivate_discount_codes(<?php echo $id ?>);" class="span_pointer"><?php _e($status); ?></span>
                                </td>
                            </tr> 
                            <?php } // foreach ($myrows as $memberships) ?>
                    
             <?php }else{ ?>
                    <tr><td><?php _e(language_code('DSP_EMPTY')) ?></td></tr>
             <?php } ?>
                </tbody> 
                <tfoot>    
                    <tr>     
                        <th><strong>Id</strong></th>
                        <th><strong><?php _e(language_code('DSP_NAME')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_DISCOUNT_DESCRIPTION')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_DISCOUNT_CODE')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_DISCOUNT_TYPE')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_DISCOUNT_AMOUNT')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_STATUS')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_DISCOUNT_USES')); ?></strong></th>
                        <th><strong><?php _e(language_code('DSP_ACTION')); ?></strong></th>
                    </tr>
               </tfoot> 
            <table>
        </form>
    
</div>
<div class="dsp_clr discount_form"></div>
<?php
if (isset($_GET['Action']) && $_GET['Action'] == 'update') {
    $mode = 'update';
    $discount_id = isset($_REQUEST['Id']) ? $_REQUEST['Id'] : '';
    $discount_code = $wpdb->get_row("SELECT * FROM $dsp_discount_codes_table WHERE id = $discount_id");
    $id = $discount_code->id;
    $name = $discount_code->name;
    $description = $discount_code->description;
    $code = $discount_code->code;
    $amount = $discount_code->amount;
    $type = $discount_code->type;
    $status = $discount_code->status;
    $uses = $discount_code->uses;
  
} else {
    $id = '';
    $name = '';
    $description = '';
    $code = '';
    $amount = '';
    $type = '';
    $status = '';
    $uses = '';
    $mode = 'add';
} // if($_GET['Action']=='update')
?>
<div id="general" class="postbox" >

    <h3 class="hndle"><span><?php echo language_code('DSP_ADD_NEW_DISCOUNT'); ?></span></h3>
    <div class="dsp_membership_wrap dsp_membership_wrap_discount">

        <form name="discountCodefrm" method="post" >

            <br>

            <div class="dsp_membership_col1" id="head" ><?php _e(language_code('DSP_NAME')); ?></div>

            <div class="dsp_membership_col6">

                <input type="text" name="discount_name" value="<?php echo $name; ?>" class="regular-text" />
                

            </div>

            <div class="dsp_clr" style="height:20px;"></div>

            <div class="dsp_membership_col1" id="head" ><?php _e(language_code('DSP_DISCOUNT_DESCRIPTION')); ?></div>

            <div class="dsp_membership_col4">
            	 <textarea name="discount_description" cols="49" rows="5" class="regular-text" style="width:299px;"><?php echo $description; ?></textarea>
            </div>

            <div class="dsp_clr" style="height:105px;"></div>

            <div class="dsp_membership_col1" id="head" ><?php _e(language_code('DSP_DISCOUNT_CODE')); ?></div>

            <div class="dsp_membership_col4">

                <input type="text" name="discount_code" value="<?php echo $code; ?>" class="regular-text" />

            </div>

            <div class="dsp_clr"  style="height:20px;"></div>

            <div class="dsp_membership_col1" id="head" ><?php _e(language_code('DSP_DISCOUNT_TYPE')); ?></div>
            <div class="dsp_membership_col5">
                <select name="discount_type" id="getting_data" > 
                 <?php if(strcmp($type,'%') == 0):
                                $first = 'selected';
                         else:
                                $second = 'selected';
                         endif;   


                   ?>      
                    <option value="0"><?php _e(language_code('DSP_SELECT_OPTION'));?></option>       
                    <option value="%"  <?php if(isset($first)):?>selected="<?php echo $first;?>" <?php endif; ?>><?php _e(language_code('DSP_PERCENTAGE'));?></option>
                    <option value="$" <?php if(isset($second)):?>selected="<?php echo $second;?>" <?php endif; ?>><?php _e(language_code('DSP_DOLLOR'));?></option>
                </select>

               
            </div>

             <div class="dsp_clr"  style="height:15px;"></div>
            <div class="dsp_membership_col1" id="head" ><?php _e(language_code('DSP_DISCOUNT_AMOUNT')); ?></div>
            <div class="dsp_membership_col5">
                 <input type="text" name="discount_amount" value="<?php echo $amount; ?>" class="regular-text" />
            </div>
            <div class="dsp_clr"  style="height:15px;"></div>
            
            <div class="dsp_membership_col1" id="head" ><?php _e(language_code('DSP_STATUS')); ?></div>
            <div class="dsp_membership_col5">
                <select name="discount_status" id="getting_data" >
                   <?php if($status == 0):
                                $Inactiveselected = 'selected';
                         else:
                                $activeSelected = 'selected';
                         endif;   

                   ?>      
                    <option value="0"  <?php if(isset($Inactiveselected)):?> selected="<?php echo $Inactiveselected;?>" <?php endif; ?>><?php echo _e(language_code('DSP_INACTIVE')); ?></option>  
                    <option value="1"   <?php if(isset($activeSelected)):?> selected="<?php echo $activeSelected;?>" <?php  endif; ?>><?php echo _e(language_code('DSP_ACTIVE')); ?></option>
                </select>

               
            </div>
            <div> <input type="hidden" name="mode" value="<?php echo $mode ?>" /></div>

            <br />

            <br />

            <input style="float:none;" type="submit" class="button button-primary" name="submit"  value="<?php _e('Save Changes') ?>" onclick=" return Checkform();"/>

        </form>

    </div>
</div>
<br />
<br />
<script>
    function Checkform() {
        if (document.discountCodefrm.discount_name.value == "")
        {
            alert('<?php _e(language_code('DSP_DISCOUNT_NAME_TEXT')); ?> ');
            document.discountCodefrm.discount_name.focus();
            return false;
        }
        if (document.discountCodefrm.discount_code.value == "")
        {
            alert('<?php _e(language_code('DSP_DISCOUNT_CODE_TEXT')); ?>');
            document.discountCodefrm.discount_code.focus();
            return false;
        }
        if (document.discountCodefrm.discount_type.value == "")
        {
            alert('<?php _e(language_code('DSP_DISCOUNT_TYPE_TEXT')); ?>');
            document.discountCodefrm.discount_type.focus();
            return false;
        }
        if (document.discountCodefrm.discount_description.value == "")
        {
            alert('<?php _e(language_code('DSP_DISCOUNT_DESCRIPTION_TEXT')); ?>');
            document.discountCodefrm.discount_description.focus();
            return false;
        }
        if (document.discountCodefrm.discount_amount.value == "")
        {
            alert('<?php _e(language_code('DSP_DISCOUNT_AMOUNT_TEXT')); ?>');
            document.discountCodefrm.discount_amount.focus();
            return false;
        }
        return true;
    }
</script>
<table width="490" border="0" cellpadding="0" cellspacing="0">
    <!--DWLayoutTable-->
    <tr>
        <td width="490" height="61" valign="top">&nbsp;</td>
    </tr>
</table>
