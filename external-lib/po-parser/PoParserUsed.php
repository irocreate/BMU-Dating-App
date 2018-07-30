<?php

 /**
  *  This is a static class which uses the PHP-po-parser to translate the *.po files 
  *  which can be found in   wp-content/po/*.po files.
  *  For more info : https://github.com/raulferras/PHP-po-parser
  */
namespace Sepia;
include_once(__DIR__.'/Sepia/InterfaceHandler.php');
include_once(__DIR__.'/Sepia/StringHandler.php');
include_once(__DIR__.'/Sepia/FileHandler.php');
include_once(__DIR__.'/Sepia/PoParser.php');

if(!class_exists('PoParserUsed')):
 	class PoParserUsed {
	 	private static $instance;
	 	protected static $poEntries;
	 	protected static $languageName;
		public static $filePath;
		protected static $_initialized = false;

	 	
	 	public static function setBasicValues($languageName,$filePath) {
	 		self::$languageName = $languageName;
	 		self::$filePath = $filePath;
	 	}

	    public static function init_po_entries() {
	    	if(
	    		!empty(self::$poEntries) && 
	    	 	array_key_exists(self::$languageName,self::$poEntries) && 
	    	 	count(self::$poEntries[self::$languageName]) > 0
	    	) {
	    		return true;
	    	}
	    	
	    	try {
	    		$parser = PoParser::parseFile(self::$filePath);
		    	self::$poEntries[self::$languageName] = $parser->getEntries();
		    	self::$_initialized = true;
	    	//var_dump(self::$poEntries);die;  
		    } catch (\Exception $e) {
	            echo $e->getMessage();
	        }

	    }

	    public static function get_translated_text($key){
	    	self::init_po_entries();
	    	$langArray = self::$poEntries[self::$languageName];
	    	$translatedText = !empty($langArray)  && array_key_exists($key,$langArray) ? $langArray[$key]['msgstr'] : null;
	    	return !empty($translatedText) ? array_pop($translatedText) : false;
	    }

	    public static function updatePo($msgId,$msgStr){
	    	try{
	    		$fileHandler = self::$filePath;
	    		//dsp_debug(self::$filePath);die;
		    	$parser = PoParser::parseFile($fileHandler);
		        $parser->setEntry($msgId, array(
		            'msgid' => $msgId,
		            'msgstr' => $msgStr
		        ));
		        return $parser->writeFile($fileHandler);
		    }catch (\Exception $e) {
	            echo $e->getMessage();
	        }
	    }
	    
	}
	
endif;
