<?php 
 /**
  *
  * This class is used to handle import language packs in SQL format 
  * for different languages like chinese,french etc..
  * 
  */
 include_once(WP_DSP_ABSPATH .'external-lib/sql-parser/sql_parser.php');
 include_once(WP_DSP_ABSPATH .'files/classes/class-file-handling.php');
 //include_once("dsp_upload_image_with_GD.php"); 

 class DspImportNewLanguage{

 	private $_avialLanguagePacks = array(
 										'ru'=>'Russian' , 
 										'ch'=>'Chinese' ,
 										'fr'=>'French' , 
 										'sp'=>'Spanish' , 
 										'po'=>'Portuguese', 
 										'du'=>'Dutch' , 
 										'ge'=>'German',
 										'it'=>'Italian',
 										'hi'=>'Hindi'  
 									);
 	private $_langName = '';
 	private $_sqlFileName = '';
 	private $_currentUserId = '';
 	private $_flagName = '';
 	// table names
 	private $_dspLang = '';
 	private $_dspLangDetail = '';
 	private $_dspProfileSetup = '';
 	private $_dspFlirt = '';
 	private $_dspProfileQuestionOption = '';
 	private $_dspSession = '';
 	private $_tableName = '';
 	
 	// upload directory
 	public $uploadDir = ''; 

 	public  $errors = array();
 	public $msg = array();


 	function __construct(){
 		global $wpdb;
 		$this->_dspLang = $wpdb->prefix . DSP_LANGUAGE_TABLE;
		$this->_dspLangDetail = $wpdb->prefix . DSP_LANGUAGE_DETAILS_TABLE;
		$this->_dspSession = $wpdb->prefix . DSP_SESSION_LANGUAGE_TABLE;
		$this->_dspProfileSetup = $wpdb->prefix . DSP_PROFILE_SETUP_TABLE;
		$this->_dspFlirt = $wpdb->prefix . DSP_FLIRT_TEXT_TABLE;
		$this->_dspProfileQuestionOption = $wpdb->prefix . DSP_PROFILE_QUESTION_OPTIONS_TABLE;
		$this->_currentUserId = get_current_user_id();
		$this->uploadDir = WP_DSP_ABSPATH . '../../uploads/flags/';
		// load all require hooks
 		$this->loadTemplateTags();
		$nonce = isset($_REQUEST['_dsp_import_nonce']) ? $_REQUEST['_dsp_import_nonce'] : '';
 		if ( !empty($nonce)){
 			if(! wp_verify_nonce( $nonce, 'dsp_import' )){
 				trigger_error('Invalid nonce value');
 			}
 			$filehandler = new FileHandler($_FILES['flag'],$this->uploadDir);
 			$filehandler->uploadFile();
 			if(empty($filehandler->errors)){
	 			$this->dspInitValues($_REQUEST,$_FILES);
	 			$this->_tableName = DSP_LANGUAGE_TABLE . '_' . strtolower($this->_langName);
	 			if($this->_dspValidateFileType($_FILES)){
	 				if($this->dspImportData()){
	 					$this->msg[] =  'Language imported Successfully';// language('DSP_IMPORTED_SUCCESSFULLY');
	 				}
	 			}
	 		}else{
	 			$this->_pushFileHandlingErrors($filehandler->errors);
	 		}
 		}
 		$this->dspView();
 	}


 		
 	/**
 	 * This method is used to validate the file type
 	 * @access private
 	 * @return void 
 	 * @since 5.0
 	 * @author neil
 	 * 
 	 */
 	
 	private function _dspValidateFileType($files) {
 		if(empty($files['language_pack']['name'])){
 			array_push($this->errors,'No Language Pack Selected');
 			if($files['language_pack']['type'] != 'text/x-sql'){
 				array_push($this->errors,'Invalid file extension');
 			}
 			return false;
 		} 
 		return true; 
 	}

  	/**
 	 * This method is used to initalize values posted from form 
 	 * @access public
 	 * @return void 
 	 * @since 5.0
 	 * @author neil
 	 * 
 	 */
 	

 	public function dspInitValues($data){
 		$this->_langName = isset($_REQUEST['lang_name']) ? $_REQUEST['lang_name'] : '';
 		$this->_sqlFileName = isset($_FILES['language_pack']['tmp_name']) ? $_FILES['language_pack']['tmp_name'] : '';
 		$this->_flagName = isset($_FILES['flag']['name']) ? $_FILES['flag']['name'] : '';
  	}

 	/**
 	 * This method is used to add errors occured during flag uploading
 	 * @access public
 	 * @return void 
 	 * @since 5.0
 	 * @author neil
 	 * 
 	 */

 	private function _pushFileHandlingErrors($errors) {
 		if(empty($errors))
 			return;
 		foreach ($errors as  $error) {
 			array_push($this->errors,$error);
 		}

 	}

 	/**
 	 * This method is used to load all custom hooks required 
 	 * @access public
 	 * @return void 
 	 * @since 5.0
 	 * @author neil
 	 * 
 	 */
 	
 	
 	public function loadTemplateTags() {

 		add_filter('dspGenerateOptionsLanguage',array(&$this,'dspGenerateOptionsLanguageFunc'));
 		add_action('dspAddUserToSessionTable',array(&$this,'dspAddUserToSessionTableFunc'),10,1);
 		 
 	}

 	 	
 	/**
 	 * This method is used to  update or insert language in
 	 * Language details table
 	 * @access public
 	 * @return void 
 	 * @since 5.0
 	 * @author neil
 	 * 
 	 */
 	
 	
 	public function dspMakeDefaultLang($langId) {
 		global $wpdb;
 		$user_id= get_current_user_id();
 		$query = "UPDATE $this->_dspLangDetail SET `display_status` = '%d' WHERE `display_status` = '%d'";
 		$currentDefaultLangId = $wpdb->get_var($wpdb->prepare($query,array(0,1)));
 		$query = "UPDATE $this->_dspLangDetail SET `display_status` = '%d' WHERE `language_id` = '%d'";
 		$wpdb->query($wpdb->prepare($query,array(1,$langId)));
 		
 		// set session value into current imported language
 		//dsp_debug($userExist);
 		do_action('dspAddUserToSessionTable',$langId);
 		
 	}

 	/**
 	 * This method is used to check language name already exist 
 	 *  or not in dsp_language_details table
 	 * @access public
 	 * @return void 
 	 * @since 5.0
 	 * @author neil
 	 * 
 	 */
 	
 	public function dspCheckLanguageNameExist()
 	{
 		global $wpdb;
 		$query = "SELECT `language_id` FROM  $this->_dspLangDetail WHERE table_name like '%s'";
 		$langId = $wpdb->get_var($wpdb->prepare($query,$this->_tableName));
 		return $langId;
 	}

 	
 	/**
 	 * This method is used to  update or insert language in
 	 * Language details table
 	 * @access public
 	 * @return void 
 	 * @since 5.0
 	 * @author neil
 	 * 
 	 */
 	
 	public function dspInsertLanguageDetails(){
 		global $wpdb;
 		$langId = $this->dspCheckLanguageNameExist();
 		if(!empty($langId)){
 			$wpdb->update(  $this->_dspLangDetail,
                            array(
                                  'language_name' => $this->_langName,
                                  'display_status'=> 0,
                                  'flag_image'=> $this->_flagName,
                                  'table_name'=> $this->_tableName,
                                  'charset'   => ''
                                ),
                            array('language_id' => $langId),
                            array('%s','%d','%s','%s','%s'),
                            array('%d')
                        );
	        $poData = dsp_get_po_data($langId);
        	$deletePoPath = PO_PATH . $poData['language_name'];
	        if(file_exists($deletePoPath)){ // removing po file & folder
	        	unlink($poData['file_path']);
	            rmdir($deletePoPath);
	        }
 		}else{
	 		$wpdb->insert(  $this->_dspLangDetail,
	                            array(
	                                  'language_name' => $this->_langName,
	                                  'display_status'=> 0,
	                                  'flag_image'=> $this->_flagName,
	                                  'table_name'=> $this->_tableName,
	                                  'charset'   => ''
	                                ),
	                            array('%s','%d','%s','%s','%s')
	                        );
	        $langId = $wpdb->insert_id;
	    }
        $this->dspMakeDefaultLang($langId);
        return $langId;
 	}

 	
 	/**
 	 * This method is used to create language table
 	 * @access public
 	 * @return void 
 	 * @since 5.0
 	 * @author neil
 	 */
 	
 	public function createTable($languageTable){
 		global $wpdb;
 		$DSP_PROFILE_TABLE_NAME = $this->_dspProfileSetup .  '_' . strtolower($this->_langName);
 		$DSP_PROFILE_QUES_TABLE_NAME = $this->_dspProfileQuestionOption .  '_' . strtolower($this->_langName);
 		$DSP_FLIRT_TABLE_NAME = $this->_dspFlirt .  '_' . strtolower($this->_langName);
 		$langId = $this->dspInsertLanguageDetails();
 		if ($wpdb->get_var("show tables like '$languageTable'") == $languageTable ){
			$this->errors[] =  str_replace("<#LANGNAME#>", $this->_avialLanguagePacks[$this->_langName], language_code('DSP_LANGUAGE_DELETE_TEXT'));
			return false;
		}
		$wpdb->query("CREATE TABLE $languageTable LIKE $this->_dspLang ");
		$wpdb->query("ALTER TABLE $languageTable  DEFAULT CHARACTER SET utf8  COLLATE utf8_unicode_ci");
		$wpdb->query("TRUNCATE TABLE $languageTable");
		
		if ($wpdb->get_var("show tables like '$DSP_PROFILE_TABLE_NAME'") != $DSP_PROFILE_TABLE_NAME) {//echo '<br>profile table name'.$DSP_PROFILE_TABLE_NAME;
            $wpdb->query("CREATE TABLE $DSP_PROFILE_TABLE_NAME AS (SELECT * FROM $this->_dspProfileSetup )");
            $wpdb->query("ALTER TABLE $DSP_PROFILE_TABLE_NAME  DEFAULT CHARACTER SET utf8  COLLATE utf8_unicode_ci");
            $wpdb->query("ALTER TABLE  $DSP_PROFILE_TABLE_NAME CHANGE  `profile_setup_id`  `profile_setup_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ");
            $wpdb->query("ALTER TABLE  $DSP_PROFILE_TABLE_NAME CHANGE  `question_name`  `question_name` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ");
            
        } 
 		

        if ($wpdb->get_var("show tables like '$DSP_PROFILE_QUES_TABLE_NAME'") != $DSP_PROFILE_QUES_TABLE_NAME) { //echo '<br>profile ques table name'.$DSP_PROFILE_QUES_TABLE_NAME;
            $wpdb->query("CREATE TABLE $DSP_PROFILE_QUES_TABLE_NAME AS (SELECT * FROM $this->_dspProfileQuestionOption )");
            $wpdb->query("ALTER TABLE $DSP_PROFILE_QUES_TABLE_NAME  DEFAULT CHARACTER SET utf8  COLLATE utf8_unicode_ci");
            $wpdb->query("ALTER TABLE  $DSP_PROFILE_QUES_TABLE_NAME CHANGE  `question_option_id`  `question_option_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ");
            $wpdb->query("ALTER TABLE  $DSP_PROFILE_QUES_TABLE_NAME  CHANGE `option_value` `option_value` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");
        }

        if ($wpdb->get_var("show tables like '$DSP_FLIRT_TABLE_NAME'") != $DSP_FLIRT_TABLE_NAME) {
            //echo '<br>profile flirt table name'.$DSP_FLIRT_TABLE_NAME;
            $wpdb->query("CREATE TABLE $DSP_FLIRT_TABLE_NAME AS (SELECT * FROM $this->_dspFlirt )");
            $wpdb->query("ALTER TABLE $DSP_FLIRT_TABLE_NAME  DEFAULT CHARACTER SET utf8  COLLATE utf8_unicode_ci");
            $wpdb->query("ALTER TABLE  $DSP_FLIRT_TABLE_NAME CHANGE  `Flirt_ID`  `Flirt_ID` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ");
            $wpdb->query("ALTER TABLE  $DSP_FLIRT_TABLE_NAME  CHANGE `flirt_Text` `flirt_Text` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL");
        }
        dsp_generate_po_file($langId,$this->_langName);
		return true;
 	}


	/**
 	 * This method is used to import sql files into its 
 	 * repected tables 
 	 * @access public
 	 * @return void 
 	 * @since 5.0
 	 * @author neil
 	 */
 	
 	public function dspImportData() {
 		global $wpdb;
 		$languageTable = $wpdb->prefix . $this->_tableName;
 		$defaultLangTable = '<#LANGUAGETABLE#>';
 		//var_dump($sqlQuery);die;
 		if($this->createTable($languageTable)){
		 		$sqlQuery = @fread(@fopen($this->_sqlFileName, 'r'), @filesize($this->_sqlFileName)) or die('problem ');
				$sqlQuery = SqlParser::remove_remarks($sqlQuery);
				$sqlQuery = SqlParser::split_sql_file($sqlQuery, ';');
				$sqlQuery = str_replace($defaultLangTable,$languageTable,$sqlQuery);
				foreach ($sqlQuery as  $query) {
					if (stripos($query,'insert') === false) {
						continue;
					}
					
					//dsp_debug($query);
					$wpdb->query($query);
				}
			return true;
		}else{
			do_action('dspAddUserToSessionTable', 1);
			return false;
		}
 	}

 	/**
 	 * This method is used to present the layouts
 	 * for handle language packs
 	 * @access public
 	 * @return void 
 	 * @since 5.0
 	 * @author neil
 	 */
 	
 	public function dspView(){ 
 		?>
 		<div id="general" class="postbox">
 			<?php if(!empty($this->errors)) { ?>
	 			<div class="error">
	 				<?php
						foreach ($this->errors as $error) {
	 						echo '<p>'. $error .'</p>';
	 					}
					?>
	 			</div>
 			<?php }
 			 if(count($this->msg) > 0) { ?>
	 			<div class="updated">
	 				<?php
						foreach ($this->msg as $msg) {
	 						echo '<p>'. $msg .'</p>';
	 					}
					?>
	 			</div>
 			<?php } ?>

    		<h3 class="hndle"><span><?php echo language_code('DSP_TOOLS_IMPORT_LANGUAGE_PACK'); ?></span></h3>
	 		 <form action="" method="post" enctype="multipart/form-data" id="import-lang" >
	            <table width="100%">
	                <tr>
	                    <td colspan="3"  style="color: red">
	                     </td>
	                </tr>
	                <tr><td>&nbsp;</td></tr>
	                <tr>
	                    <td width="25%"><?php echo language_code('DSP_LANGUAGE_NAME'); ?>:</td>
	                    <td>
	                    	<?php 
	                    		echo apply_filters('dspGenerateOptionsLanguage','');
                    		?>	
	                   		
	                   	</td>
	                    <td> <?php echo language_code('DSP_IMPORT_LANGUAGE_INFO'); ?></td>
	                    <td>&nbsp;</td>                     
	                </tr>
	                <tr>
	                    <td><?php echo language_code('DSP_LANGUAGE_PACK'); ?>:</td>
	                    <td><input id="language_pack" type="file" name="language_pack" size="18"  value="<?php echo $this->fileName;?>" accept="application/sql" /></td>
	                    <td> <?php echo language_code('DSP_LANGUAGE_PACK_INFO'); ?></td>
	                    <td>&nbsp;</td>   
	                </tr>
	                <tr>
                        <td><?php echo language_code('DSP_FLAG_IMAGE'); ?>:</td>
                        <td><input id="default_image" type="file" name="flag" size="18" accept="image/*" value=""/></td>
                        <td >
                            <a href="<?php echo WPDATE_URL . '/flags/flags.zip' ?>" style="color: red"><?php echo language_code('DSP_DOWNLOAD_FLAG_FIRST'); ?></a>
                            <?php if (isset($flagImage)) { ?><img src="<?php echo get_bloginfo('url') . '/wp-content/uploads/flags/' . $flagImage; ?>" /> <?php } ?>
                    	</td>
                    </tr> 
	                <tr>
	                    <td>&nbsp;</td>
	                    <td>
	                       <input type="hidden" value="<?php echo wp_create_nonce('dsp_import'); ?>" name="_dsp_import_nonce" />
	                       <input type="submit" class="import"  value="<?php echo language_code('DSP_TOOLS_IMPORT_LANGUAGE_PACK'); ?>" name="import_lang" />
	                    </td>
	                </tr>
	            </table>
	        </form>
	    <?php
 	}

 
 	/**
 	 * This method is used to generate select option
 	 * for language packs which is works as helper for view
 	 * @access private
 	 * @return void 
 	 * @since 5.0
 	 * @author neil
 	 */
 
 	public function dspGenerateOptionsLanguageFunc($_langName) {
 		$options = '<select name="lang_name">';
 		foreach ($this->_avialLanguagePacks as $key => $langPack){
		   $selected = $this->_langName  == $langPack ? 'selected="selected"' : '';
		   $options .= '<option value="'. $key .'" '. $selected .'>' . $langPack .'</option>';
		}
		$options .= '</select>';
		return $options;
 	}


 	/**
 	 * This method is used to add current user into session table
 	 * @access public
 	 * @return void 
 	 * @since 5.0
 	 * @author neil
 	 */
 
 	public function dspAddUserToSessionTableFunc($langId) {
 		global $wpdb;
 		$user_id= get_current_user_id();
 		// check for wheather or not user exist in session table
 		$userExist = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $this->_dspSession WHERE user_id='%d' ",$user_id));
 		$query = ($userExist >  0) ? "UPDATE " : " INSERT INTO ";
 		$query .= "  $this->_dspSession SET language_id ='%d' WHERE user_id='%d'";
 		$wpdb->query($wpdb->prepare($query,array($langId,$this->_currentUserId)));
 	}
 	
 }

 new DspImportNewLanguage();