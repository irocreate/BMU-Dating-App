<?php
/**
 *This class is used to handle all files upload & delete
 * works.
 *
 * @package FileHandler 
 * @author neil
 * @since 5.0
 */

!defined('MAX_SIZE') ? define("MAX_SIZE", 2000) : '';
!defined('WIDTH') ? define("WIDTH", "24") : '';
!defined('HEIGHT') ? define("HEIGHT", "24") : '';
!defined('DIMENSION') ? define("DIMENSION", "24") : '';
!defined('MIN_WIDTH') ? define("MIN_WIDTH", "24") : '';
!defined('MIN_HEIGHT') ? define("MIN_HEIGHT", "24") : '';
!defined('MAX_WIDTH') ? define("MAX_WIDTH", "50") : ''; // we have removed max width and max height option client don't want this'
!defined('MAX_HEIGHT') ? define("MAX_HEIGHT", "50") : '';

class FileHandler {

	private $_uploadPath = '';
	private $_fileDetails = '';
	public $errors = array();
	protected $_validExtension = array( "jpg","jpeg","png","gif");


	function __construct($filesDetails='',$uploadPath = '') {
		$this->_fileDetails = $filesDetails;
		$this->_uploadPath  = empty($uploadPath) ? wp_upload_dir() : $uploadPath;
	}

	/**
	 *
	 * This method is used to extract the extension from provided  
	 * file name 
	 * @access private
 	 * @since 5.0
 	 * @param  String $str 
	 * @return String
	 * @author neil
	 */
	
	private function _getExtension($fileName) {

	    $i = strrpos($fileName, ".");

	    if (!$i) {
	        return "";
	    }

	    $l = strlen($fileName) - $i;
	    $ext = substr($fileName, $i + 1, $l);
	    return $ext;
	}

	/**
	 *
	 * This method is used to generate the thumb from 
	 * given information
	 * @access public
 	 * @since 5.0
 	 * @param  String $str 
	 * @return String
	 * @author neil
	 * 
	 */
	
	public function _generateThumb($src_image, $dest_image, $thumb_size = DIMENSION, $jpg_quality = 90) {
		// Get dimensions of existing image
	    $image = getimagesize($src_image);

	    // Check for valid dimensions
	    if ($image[0] <= 0 || $image[1] <= 0)
	        return false;

	    // Determine format from MIME-Type
	    $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));

	    // Import image
	    switch ($image['format']) {
	        case 'jpg':
	        case 'jpeg':
	            $image_data = imagecreatefromjpeg($src_image);
	            break;
	        case 'png':
	            $image_data = imagecreatefrompng($src_image);
	            break;
	        case 'gif':
	            $image_data = imagecreatefromgif($src_image);
	            break;
	        default:
	            // Unsupported format
	            return false;
	            break;
	    }

	    // Verify import
	    if ($image_data == false)
	        return false;

	    // Calculate measurements
	    if ($image[0] > $image[1]) {
	        // For landscape images
	        $x_offset = ($image[0] - $image[1]) / 2;
	        $y_offset = 0;
	        $square_size = $image[0] - ($x_offset * 2);
	    } else {
	        // For portrait and square images
	        $x_offset = 0;
	        $y_offset = ($image[1] - $image[0]) / 2;
	        $square_size = $image[1] - ($y_offset * 2);
	    }

	    // Resize and crop
	    $canvas = imagecreatetruecolor($thumb_size, $thumb_size);



	    if (imagecopyresampled(
	            $canvas, $image_data, 0, 0, $x_offset, $y_offset, $thumb_size, $thumb_size, $square_size, $square_size
	        )) {

	        // Create thumbnail
	        switch (strtolower(preg_replace('/^.*\./', '', $dest_image))) {
	            case 'jpg':
	            case 'jpeg':
	                return imagejpeg($canvas, $dest_image, $jpg_quality);
	                break;
	            case 'png':
	                return imagepng($canvas, $dest_image);
	                break;
	            case 'gif':
	                return imagegif($canvas, $dest_image);
	                break;
	            default:
	                // Unsupported format
	                return false;
	                break;
	        }
	    } else {
	        return false;
	    }
	}


	/**
	 *
	 * This method is used to generate the thumb from 
	 * given information
	 * @access public
 	 * @since 5.0
 	 * @param  String $str 
	 * @return String
	 * @author neil
	 * 
	 */
	
	public function uploadFile() {
		// get the original name of the file from the clients machine
		$fileName = stripslashes($this->_fileDetails['name']);
		$tmpName = $this->_fileDetails['tmp_name'];

		$errors = '';
		//reads the name of the file the user submitted for uploading
        // if it is not empty
        if ($fileName) {
            // get the extension of the file in a lower case format
            $extension = strtolower($this->_getExtension($fileName));
           
            // if it is not a known extension, we will suppose it is an error, print an error message
            //and will not upload the file, otherwise we continue
            if (!in_array($extension,$this->_validExtension)) {
                $this->errors[] = language_code('DSP_USER_UNKNOWN_EXTENSION_FOR_IMAGE') . '<br>';
                $invalidEntry = 1;
                $errors = 1;
                $fileName = "";
                return false;
            } else {
                $size = getimagesize($this->_fileDetails['tmp_name']);
            	$width = $size[0];
                $height = $size[1];

                $sizekb = filesize($tmpName);

                //compare the size with the maxim size we defined and print error if bigger
                if ($sizekb > MAX_SIZE * 1024) {
                    $this->errors[] = language_code('DSP_USER_IS_TOO_LARGE_PLEASE_REDUCE_UR_IMAGE_BELOW') . MAX_SIZE . DSP_USER_KB . '<br>';
                    $errors = 1;
                    $fileName = "";
                    $invalidEntry = 1;
                    return false;
                }

                //compare the size with the minimum  we defined and print error if bigger
                if ($height < MIN_HEIGHT || $width < MIN_WIDTH) {
                    $this->errors[] = language_code('DSP_MINIMUM') . MIN_HEIGHT . 'x' . MIN_WIDTH . language_code('DSP_USER_DIMENSION_IS_REQUIRED') . '<br>';
                    $errors = 1;
                    $fileName = "";
                    $invalidEntry = 1;
                    return false;
                }

                $newName = $this->_uploadPath . $fileName;
                // upload file from tmp location
                $copied = move_uploaded_file($tmpName, $newName);
                               
                if (!$copied) {
                    //echo '<br>can not copy';
                    $this->errors[] = 'Cannot copy';
                    $invalidEntry = 1;
                    $errors = 1;
                    $fileName = "";
                    return false;
                } else {
                    // the new thumbnail image will be placed in images/thumbs/ folder
                    $thumb_name = $this->_uploadPath . DIRECTORY_SEPARATOR . $fileName;
                    // call the function that will create the thumbnail. The function will get as parameters
                    $thumb = $this->_generateThumb($newName, $thumb_name);
                   return true;
                } // end of else
            }
        } // end default image upload

	}



}