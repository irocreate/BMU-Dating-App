<?php

//echo 'kkk';
//define('FOPEN_WRITE_CREATE','ab');

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');
$config = array();

/* End of file constants.php */

$config['log_threshold'] = 4;

/*
 * |--------------------------------------------------------------------------
 * | Error Logging Directory Path
 * |--------------------------------------------------------------------------
 * |
 * | Leave this BLANK unless you would like to set something other than the default
 * | system/logs/ folder.  Use a full server path with trailing slash.
 * |
 * */
$config['log_path'] = dirname(plugin_basename(__FILE__)) . "/logs";


/*
 * |--------------------------------------------------------------------------
 * | Date Format for Logs
 * |--------------------------------------------------------------------------
 * |
 * | Each item that is logged has an associated date. You can use PHP date
 * | codes to set your own date formatting
 * |
 * */
$config['log_date_format'] = 'Y-m-d H:i:s';

$log_path;
$config;
$_threshold = 4;
$_date_fmt = 'Y-m-d H:i:s';
$_enabled = TRUE;
$_levels = array('ERROR' => '1', 'DEBUG' => '2', 'INFO' => '3', 'ALL' => '4');

/**
 * Tests for file writability
 *
 * is_writable() returns TRUE on Windows servers when you really can't write to 
 * the file, based on the read-only attribute.  is_writable() is also unreliable
 * on Unix servers if safe_mode is on. 
 *
 * @access	private
 * @return	void
 */
function is_really_writable($file) {
    global $log_path, $_threshold, $_date_fmt, $_enabled, $_levels;
// If we're on a Unix server with safe_mode off we call is_writable

    if (DIRECTORY_SEPARATOR == '/' AND @ ini_get("safe_mode") == FALSE) {
        return is_writable($file);
    }

// For windows servers and safe_mode "on" installations we'll actually
// write a file then read it.  Bah...
    if (is_dir($file)) {
        $file = rtrim($file, '/') . '/' . md5(rand(1, 100));

        if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE) {
            return FALSE;
        }

        fclose($fp);
        @chmod($file, DIR_WRITE_MODE);
        @unlink($file);
        return TRUE;
    } elseif (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === FALSE) {
        return FALSE;
    }

    fclose($fp);
    return TRUE;
}

/**
 * Constructor
 *
 * @access	public
 */
function log_init() {

    global $log_path, $_threshold, $_date_fmt, $_enabled, $_levels;
//$log_path = getcwd()."/wp-content/plugins/".dirname(plugin_basename(__FILE__)).'/logs/';
    $log_path = getcwd() . '/logs/'; // i hv change the path for mobile app .. for desktop avove path will work
//echo $log_path;
    if (!is_really_writable($log_path)) {
//echo '<br>'.$log_path.' not writable';
        $_enabled = FALSE;
    }
    if (!is_dir($log_path)) {
//echo 'not dir139';
        $_enabled = FALSE;
    }

//if (is_numeric($config['log_threshold'])) {
//  $_threshold = $config['log_threshold'];
//}
// if ($config['log_date_format'] != '') {
//   $_date_fmt = $config['log_date_format'];
//}
}

// --------------------------------------------------------------------

/**
 * Write Log File
 *
 * Generally this function will be called using the global log_message() function
 *
 * @access	public
 * @param	string	the error level
 * @param	string	the error message
 * @param	bool	whether the error is a native PHP error
 * @return	bool
 */
function write_log($level = 'error', $msg, $php_error = FALSE) {
    global $log_path, $_threshold, $_date_fmt, $_enabled, $_levels;
    if ($_enabled === FALSE) {
//echo 'not enable';
        return FALSE;
    }

    $level = strtoupper($level);

    if (!isset($_levels[$level]) OR ( $_levels[$level] > $_threshold)) {
//	echo 'not enable 177';
        return FALSE;
    }

    $filepath = $log_path . 'log-' . date('Y-m-d') . '.txt';
    $message = '';

    if (!file_exists($filepath)) {
//echo 'not enable path not ';
        $message .= "<" . "?php  if ( ! defined('base_url')) exit('No direct script access allowed'); ?" . ">\n\n";
    }

    if (!$fp = @fopen($filepath, FOPEN_WRITE_CREATE)) {
//echo 'not enable191';
        return FALSE;
    }

    $message .= $level . ' ' . (($level == 'INFO') ? ' -' : '-') . ' ' . date($_date_fmt) . ' --> ' . $msg . "\n";

    flock($fp, LOCK_EX);
    fwrite($fp, $message);
    flock($fp, LOCK_UN);
    fclose($fp);

    @chmod($filepath, FILE_WRITE_MODE);
    return TRUE;
}

/**
 * * Error Logging Interface
 * *
 * * We use this as a simple mechanism to access the logging
 * * class and send messages to be logged.
 * *
 * * @access       public
 * * @return       void
 * */
function log_message($level = 'error', $message, $php_error = FALSE) {
    static $LOG;
    global $log_path, $_threshold, $_date_fmt, $_enabled, $_levels;

    $_threshold = 4;
    if ($_threshold == 0) {
        return;
    }

//write_log($level, $message, $php_error);
}

?>