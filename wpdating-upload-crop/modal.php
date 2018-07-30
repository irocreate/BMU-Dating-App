<div id="profile_pic_modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content" id="profile_pic_modal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Change Profile Picture</h3>
            </div>
            <div class="modal-body">
                <form id="wpdating-profile-crop-image" method="post" enctype="multipart/form-data" action="<?php echo WPDATING_PROFILE_PICTURE_URL.'change-pic.php'; ?>">
                    <?php wp_nonce_field('wpdating-profile-pic-change-form', '_wpnonce_wpdating-profile-pic-change-form'); ?>
                    <strong>Upload Image:</strong> <br><br>
                    <input name="action" type="hidden" value="wpdating-profile-crop-image-action">

                    <div class="file-upload">
                        <div class="file-select">
                            <div class="file-select-button" id="fileName">Choose Image</div>
                            <div class="file-select-name" id="noFile">No image chosen...</div>
                            <input type="file" name="profile-pic" id="profile-pic">
                        </div>
                    </div>

                    <input type="hidden" name="hdn-profile-id" id="hdn-profile-id" value="<?php echo $this->user_id; ?>" />
                    <input type="hidden" name="hdn-x1-axis" id="hdn-x1-axis" value="" />
                    <input type="hidden" name="hdn-y1-axis" id="hdn-y1-axis" value="" />
                    <input type="hidden" name="hdn-x2-axis" value="" id="hdn-x2-axis" />
                    <input type="hidden" name="hdn-y2-axis" value="" id="hdn-y2-axis" />
                    <input type="hidden" name="hdn-thumb-width" id="hdn-thumb-width" value="" />
                    <input type="hidden" name="hdn-thumb-height" id="hdn-thumb-height" value="" />
                    <input type="hidden" name="image_name" value="" id="image_name" />
                    <div id='preview-profile-pic'></div>
                    <div id="thumbs" style="padding:5px; width:600px"></div>
                </form>
                <span><p>Max Upload Size: 5 MB </p></span>


            </div>
            <div class="modal-footer">
                <button type="button" class="dsp_submit_button dspdp-btn dspdp-btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="save_crop" class="dsp_submit_button dspdp-btn dspdp-btn-default">Save</button>
            </div>
        </div>
    </div>
</div>