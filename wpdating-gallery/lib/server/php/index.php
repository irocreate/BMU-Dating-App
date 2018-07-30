<?php

/*
 * jQuery File Upload Plugin PHP Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */
require('UploadHandler.php');

$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
require_once($parse_uri[0] . 'wp-load.php');

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;
$album_id     = $_REQUEST['albumId'];
$upload_path  = ABSPATH . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/album_" . $album_id . "/";

$options = array(
    'upload_dir' => $upload_path,
    'upload_url' => site_url() . "/wp-content/uploads/dsp_media/user_photos/user_" . $user_id . "/album_" . $album_id . "/",
);

class CustomUploadHandler extends UploadHandler
{
    private $dsp_galleries_photos;
    private $current_user;
    private $user_id;
    private $dsp_general_settings_table;
    private $dsp_user_albums_table;
    private $dsp_tmp_galleries_photos_table;
    private $album_id;

    public function __construct($options = null, $initialize = true, $error_messages = null, $album_id = '')
    {
        global $wpdb;
        $this->dsp_galleries_photos           = $wpdb->prefix . DSP_GALLERIES_PHOTOS_TABLE;
        $this->current_user                   = wp_get_current_user();
        $this->user_id                        = $this->current_user->ID;
        $this->dsp_general_settings_table     = $wpdb->prefix . DSP_GENERAL_SETTINGS_TABLE;
        $this->dsp_user_albums_table          = $wpdb->prefix . DSP_USER_ALBUMS_TABLE;
        $this->dsp_tmp_galleries_photos_table = $wpdb->prefix . DSP_TMP_GALLERIES_PHOTOS_TABLE;
        $this->album_id                       = $album_id;
        parent::__construct($options, $initialize, $error_messages);
    }

    protected function handle_form_data($file, $index)
    {
        $file->albumId      = $_REQUEST['albumId'];
        $file->user_id      = $this->user_id;
        $file->created_date = date("Y-m-d H:i:s");
    }

    protected function get_upload_path($file_name = null, $version = null)
    {
        $path = parent::get_upload_path($file_name, $version);

        return $path;
    }

    protected function validate($uploaded_file, $file, $error, $index)
    {
        parent::validate($uploaded_file, $file, $error, $index);
        if ($this->count_uploaded_images()) {
            return true;
        }

        $limit_msg         = language_code('DSP_UPLOAD_PICTURE_LIMIT');
        $check_image_count = $this->image_count();
        $print_limit_msg   = str_replace("<#COUNT#>", $check_image_count->setting_value, $limit_msg);
        $file->error       = $print_limit_msg;

        return false;

    }

    protected function get_file_objects($iteration_method = 'get_file_object')
    {
        /**
         * Here the images from the dsp_galleries_photo is taken and the images from user_photos is taken
         * and only those images are displayed that has status id = 1 in the table and also image that exists on the user_photos directory
         */
        $files        = parent::get_file_objects($iteration_method = 'get_file_object');
        $photos       = $this->get_image_from_db();
        $array_files  = [];
        $array_photos = [];
        $array3       = [];
        $array_final  = [];
        foreach ($files as $key => $value) {
            $array_files[$key] = $value->name;
        }
        foreach ($photos as $key => $value) {
            $array_photos[$key] = $value->image_name;
        }

        foreach ($array_photos as $key => $value) {
            foreach ($array_files as $key1 => $value1) {
                if ($value == $value1) {
                    $array3[$key] = $value;
                }
            }
        }

        $i = 0;
        foreach ($files as $key => $value) {
            $flag = 0;
            foreach ($array3 as $key1) {
                if ($value->name == $key1) {
                    $flag = 1;
                }
            }
            if ($flag == 1) {
                $array_final[$i]['name']         = $value->name;
                $array_final[$i]['size']         = $value->size;
                $array_final[$i]['url']          = $value->url;
                $array_final[$i]['thumbnailUrl'] = $value->thumbnailUrl;
                $array_final[$i]['deleteUrl']    = $value->deleteUrl;
                $array_final[$i]['deleteType']   = $value->deleteType;
                $i++;
            }
        }

        return $array_final;
    }

    protected function handle_file_upload(
        $uploaded_file,
        $name,
        $size,
        $type,
        $error,
        $index = null,
        $content_range = null
    ) {
        global $wpdb;
        $file = parent::handle_file_upload(
            $uploaded_file, $name, $size, $type, $error, $index, $content_range
        );

        if (isset($file->error)) {
            return $file;
        }

        if ($this->check_photo_approve_status()) {
            $wpdb->query("INSERT INTO $this->dsp_galleries_photos SET album_id = $file->albumId, user_id='$file->user_id', image_name = '$file->name' ,date_added= '$file->created_date',status_id=1");
            $file->message = language_code('DSP_UPLOAD_SUCESS');
            if ( ! function_exists('dsp_add_news_feed')) {
                dsp_add_news_feed($this->user_id, 'gallery_photo');
            }

            if (function_exists('dsp_add_notification')) {
                dsp_add_notification($this->user_id, 0, 'gallery_photo');
            }


        } else {
            $wpdb->query("INSERT INTO $this->dsp_galleries_photos SET album_id = $file->albumId,user_id='$file->user_id', image_name = '$file->name', date_added= '$file->created_date',status_id=0");
            $file->message = language_code('DSP_PICTURE_UPDATE_IN_HOURS_MSG');
            $insertid1     = $wpdb->insert_id;
            $wpdb->query("INSERT INTO $this->dsp_tmp_galleries_photos_table (gal_image_id ,gal_user_id ,gal_status_id) VALUES ('$insertid1', '$this->user_id', '0')");
        }

        return $file;

    }

    /**
     * Get Image details from Database
     * @return array|null|object
     */
    protected function get_image_from_db()
    {
        global $wpdb;
        $exists_photos = $wpdb->get_results("SELECT * FROM $this->dsp_galleries_photos galleries WHERE galleries.status_id= 1 AND galleries.user_id= '$this->user_id'  AND galleries.album_id = $this->album_id");

        return $exists_photos;
    }

    protected function set_additional_file_properties($file)
    {
        parent::set_additional_file_properties($file);
        $url             = $file->deleteUrl . $this->get_query_separator($file->deleteUrl) . 'albumId=' . $this->album_id;
        $file->deleteUrl = $url;
    }

    public function delete(
        $print_response = true
    ) {
        global $wpdb;
        $response = parent::delete(false);

        foreach ($response as $name => $deleted) {
            if ($deleted) {
                $wpdb->query("DELETE FROM $this->dsp_galleries_photos WHERE image_name = '$name' AND user_id = '$this->user_id' AND album_id = '$this->album_id'");
            }
        }

        return $this->generate_response($response, $print_response);
    }

    /**
     * Check if the user can upload more images
     * @return bool
     */
    public function count_uploaded_images()
    {
        global $wpdb;
        $check_image_count = $this->image_count();

        $dsp_album_id = $wpdb->get_results("SELECT * FROM $this->dsp_user_albums_table WHERE user_id = $this->user_id");
        foreach ($dsp_album_id as $id) {
            $album_ids[] = $id->album_id;
        }
        if (isset($album_ids) && $album_ids != "") {
            $ids1 = implode(",", $album_ids);
        }
        $untitled_album_id     = 0;
        $count_uploaded_images = $wpdb->get_var("SELECT COUNT(*) as Num FROM $this->dsp_galleries_photos where album_id IN ($untitled_album_id, $ids1)  AND user_id = $this->user_id AND status_id=1");
        if ($count_uploaded_images < $check_image_count->setting_value) {
            return true;
        }

        return false;

    }

    /**
     * Check DSP Admin setting to know the maximum number of image an user can upload
     * @return array|null|object|void
     */
    public function image_count()
    {
        global $wpdb;
        $check_image_count = $wpdb->get_row("SELECT * FROM $this->dsp_general_settings_table  WHERE setting_name = 'count_image'");

        return $check_image_count;
    }

    public function check_photo_approve_status()
    {
        global $wpdb;
        $check_approve_photos_status = $wpdb->get_row("SELECT * FROM $this->dsp_general_settings_table WHERE setting_name = 'authorize_photos'");


        if ($check_approve_photos_status->setting_status == 'Y') {
            return true;
        }

        return false;
    }
}


$upload_handler = new CustomUploadHandler($options, true, null, $album_id);