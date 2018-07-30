<?php
if (! function_exists( 'wp_handle_upload')) require_once(ABSPATH . 'wp-admin/includes/file.php');
$upload_template_path = ABSPATH . 'wp-content/uploads/template_images/';
$caption = isset($_REQUEST['caption']) ? $_REQUEST['caption'] : '';
$mode = isset($_REQUEST['submit']) ? $_REQUEST['submit'] : '';
$status = isset($_REQUEST['display_status']) ? $_REQUEST['display_status'] : '';
$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';
$filetype = isset($_REQUEST['filetype']) ? $_REQUEST['filetype'] : '';
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
define("MAX_SIZE", "100000");
$root_link = $_SERVER['PHP_SELF'] . '?page=dsp-admin-sub-page2&pid=template_images';
//echo $mode;die;
//var_dump($_FILES['template_image']);die;
if(isset($_POST['submit'])){ 
   switch ($mode) { 
   	case 'Add':
   	   if (!empty($url) && !empty($caption)) { 

                $errors = 0;
                $template_info = array(
                                            'caption' => $caption,
                                            'url' => $url,
                                            'file_type' => $filetype,
                                            'display_status'=>$status
                                            );
                if(dsp_insert_template_values($template_info)){
                    echo language_code('DSP_UPLOAD_SUCESS') . "\n";
                }else{
                    echo language_code('DSP_FILE_NOT_UPLOADED_MSG') . "\n";
                }
        }
   		break;
   	
   	case 'edit':
        if(!empty($url) && !empty($caption)) { 
                $errors = 0;
                $template_info = array(
                                'caption' => $caption,
                                'url' => $url,
                                'file_type' => $filetype,
                                'display_status'=>$status
                                );
                if(dsp_insert_template_values($template_info,$id)){
                    echo language_code('DSP_UPLOAD_SUCESS') . "\n";
                }else{
                    echo language_code('DSP_FILE_NOT_UPLOADED_MSG') . "\n";
                }
            }
        break;
   }

}
?>

<?php 
   
   if (isset($_REQUEST['Action']) && $_REQUEST['Action'] == "delete" && isset($_REQUEST['id']) && $_REQUEST['id'] != '') {
       $id = $_REQUEST['id'];
       $template_image = dsp_get_all_template_image($id);
       if(!empty($template_image)){
           dsp_destroy_template_image($id);
           $deleteImagePath =  $template_image->url;
           unlink($deleteImagePath);
        }
  }

?>

<div id="general" class="postbox">
	<table border="0" cellspacing="5" cellpadding="5" width="45%" class="wp-list-table widefat fixed users">
       <thead >
            <tr>
                <th><?php echo language_code('DSP_TEMPLATE_IMAGES');?></th>
                <th><?php echo language_code('DSP_IMAGE_CAPTION');?></th>
                <th><?php echo language_code('DSP_STATUS');?></th>
                <th><?php echo language_code('DSP_ACTION');?></th>
            </tr>
        <thead>
        <tbody>
        <?php
        $template_image = dsp_get_all_template_image();
        if (isset($template_image) && !empty($template_image)) :
            foreach ($template_image as $img) { 
                   $id = $img->id;
                   $imagePath = $img->url;
                   //$image_info = "name:".substr($img->template_image,0,strrpos($img->template_image, ".")-1);
                   //$image_info .= "\nsize:".$img->size;
                   $imageInfo .=  $img->caption;
                   
                   
                 ?>
                
                    <tr>
                        
                        <td style=" font-size:10px;">
                            <img width="60"  height="60" src="<?php echo $imagePath; ?>" title="<?php echo $imageInfo;?>" alt="<?php echo $imageInfo;?>"/>
                        </td>
                        <td width="50%" style=" font-size:15px;">
                            <?php echo $img->caption; ?>
                        </td>
                        <td >
                            <?php if($img->display_status == 'N'): 
                                    _e('Inactive');
                            else:
                                   _e('Active');
                            endif;
                             ?>
                        </td>
                        <td style=" font-size:15px;">
                            <a href="<?php echo $root_link . "&Action=edit&id=" . $id; ?>"><?php echo language_code('DSP_EDIT'); ?></a>
                            <span>-</span>
                            <a href="<?php echo $root_link . "&Action=delete&id=" . $id; ?>" onclick="return confirm('Are you sure you wanna delete?');"><?php echo language_code('DSP_DELETE'); ?></a>
                        </td>
                    </tr>
                 
            <?php }?>
        <?php else : ?>
            <tr>
               <td style=" font-size:10px;">
                    <?php echo language_code('DSP_EMPTY');?>
                </td>
            </tr>            
        <?php endif; ?>    
      </tbody>   
      <tfoot>
            <tr>
                <th><?php echo language_code('DSP_TEMPLATE_IMAGES');?></th>
                <!-- <th><?php echo language_code('DSP_NAME');?></th> -->
                <th><?php echo language_code('DSP_IMAGE_CAPTION');?></th>
                <th><?php echo language_code('DSP_STATUS');?></th></th>
                <th><?php echo language_code('DSP_ACTION');?></th></th>
            </tr>
        </tfoot>  
	</table>
</div>
<div class="dsp_clr"></div>
<?php
if (isset($_GET['Action']) && $_GET['Action'] == 'edit') {
    $mode = 'edit';
    $id = isset($_GET['id'])?$_GET['id']:'';
    $template_image = dsp_get_all_template_image($id);
    $caption = $template_image[0]->caption;
    $status = $template_image[0]->display_status;
    $imagePath = $template_image[0]->url;//get_bloginfo('url') . '/wp-content/uploads/template_images/' . $template_image[0]->template_image;
    //$image_info = "name:".substr($template_image[0]->template_image,0,strrpos($template_image[0]->template_image, ".")-1);
    //$image_info .= "\nsize:".$template_image[0]->size;
    $imageInfo .=  $template_image[0]->caption;
    $type = $template_image[0]->filetype;

} else {
    $mode = 'add';
    $caption = '';
    $status = '';
    $imagePath = '';
    $image_info = " "; 
   
} // if($_GET['Action']=='update')
?>
<div id="general" class="postbox">
 <h3 class="hndle"><?php echo language_code('DSP_UPLOAD_TEMPLATE_IMAGES'); ?></h3>
<form action="" method="post" name="templateImageUploadFrm" enctype="multipart/form-data" class="upload-template upload-template-image">
    <table width="50%">
        <tr>
            <td colspan="3"  style="color: red">
                <?php
                if (isset($msg)) {
                    echo $msg;
                }
                ?>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td><?php echo language_code('DSP_IMAGE_CAPTION'); ?>:</td>
            <td><input id="txt_image_caption" type="text" value="<?php if (isset($caption)) echo $caption; ?>" name="caption" /></td>
            <td>&nbsp;</td>                     
        </tr>

      
        <tr>
            <td><?php echo language_code('DSP_STATUS'); ?>:</td>
            <td><select name="display_status">
                        <?php
                        if ($status == 'Y') {
                            ?>
                            <option value="Y" selected="selected"><?php echo language_code('DSP_OPTION_YES');?></option>
                            <option value="N"><?php echo language_code('DSP_OPTION_NO');?></option>
                            <?php
                        } else {
                            ?>
                            <option value="Y"><?php echo language_code('DSP_OPTION_YES');?></option>
                            <option value="N"  selected="selected"><?php echo language_code('DSP_OPTION_NO');?></option>
                        <?php } ?>
                    </select></td>
           
        <tr>
         <tr>
            <td><?php echo language_code('DSP_UPLOAD_TEMPLATE_IMAGES'); ?>:</td>
            <td>
                <input type="hidden" id="template_upload_image_type" name="filetype" value="<?php echo $type ?>" />
                <input id="template_upload_image" type="hidden" size="36" name="url" value="" class="wpss_text wpss-file" />
                <div id="wpss_upload_image_thumb" class="wpss-file">
                    <div class="upload-image-text-block">
                        <p class="description"><?php echo language_code('DSP_TEMPLATE_IMAGE_INFO_TEXT'); ?></p>
                        <input id="template_image" type="button" name="template_image" class="upload-button" size=""  accept="image" value="Upload"/>
                    </div>
                    <img src="<?php if(!empty($imagePath)) echo $imagePath; else echo site_url(). '/wp-content/uploads/2015/01/banner6.gif' ?>"  width="65" class="template_image" alt="banner6" />
                    <div class="clear"></div>
                </div>
            </td>
            
        <tr>
            <td>&nbsp;</td>
            <td>
                <input type="hidden" name="mode" value="<?php echo $mode ?>" />

                <input type="hidden" name="editLangId" value="<?php echo $image_id; ?>" />
                <input class="upload-template-submit" type="submit" value="<?php
                if ($mode == 'add')
                    _e('Add');
                else
                    _e('edit');
                  
                ?>" name="submit"  onclick=" return Checkform();"/>
            </td>
        </tr>
    </table>
</form>
</div>
<script >
    function Checkform() {
        if (document.templateImageUploadFrm.txt_image_caption.value == "")
        {
            alert('<?php language_code('DSP_CHOOSE_CAPTION_MESSAGE'); ?>');
            document.templateImageUploadFrm.txtmembership_name.focus();
            return false;
        }
        if (document.templateImageUploadFrm.display_status.value == "")
        {
            alert('<?php language_code('DSP_CHOOSE_STATUS_MESSAGE'); ?>');
            document.templateImageUploadFrm.txtmembership_price.focus();
            return false;
        }
        
        if (document.templateImageUploadFrm.template_image.value == "")
        {
            alert('<?php language_code('DSP_CHOOSE_IMAGE_FILE_MESSAGE'); ?>');
            document.templateImageUploadFrm.dsp_mem_image.focus();
            return false;
        }
        return true;
    }

    //jquery for using builtin wordpress media 

    // Uploading files
    var file_frame;
    jQuery('#template_image').live('click', function( event ){
        event.preventDefault();
        // If the media frame already exists, reopen it.
        if ( undefined !== file_frame ) {
            file_frame.open();
         return;
     
        }
        
        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery( this ).data( 'uploader_title' ),
            button: {text: jQuery( this ).data( 'uploader_button_text' ),},
            multiple: false // Set to true to allow multiple files to be selected
        });
        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
        // We set multiple to false so only get one image from the uploader
        attachment = file_frame.state().get('selection').first().toJSON();
        jQuery('img.template_image').attr('src',attachment.url);
        jQuery('#template_upload_image').val(attachment.url);
        jQuery('#txt_image_caption').val(attachment.title);
        jQuery('#template_upload_image_type').val(attachment.subtype);
        
        });
        // Finally, open the modal
        file_frame.open();
    }); 
</script>
