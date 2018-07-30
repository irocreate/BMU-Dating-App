<?php 

$appId =  get_facebook_login_setting('facebook_api_key');
$secretfb =  get_facebook_login_setting('facebook_secret_key');

 $siteUrl = site_url() . '/';

 //facebook application
$fbconfig['appid' ]     = $appId;
$fbconfig['secret']     = $secretfb;
$fbconfig['baseurl']    = $siteUrl; 

$user =  null; //facebook user uid
try{
    include_once(__DIR__ . "/src/facebook.php");
}catch(Exception $o){
        error_log($o);
}
// Create our Application instance.
$facebook = new Facebook(array(
      'appId'  => $fbconfig['appid'],
      'secret' => $fbconfig['secret'],
      'cookie' => true,
    ));
 //Get user token
 $user = $facebook->getUser();
 

 //dsp_debug($facebook);die;
 //dsp_debug($user);die;
 if($user == 0  || empty($_REQUEST['code'])){ 
     //login url
     
    $GLOBALS['loginUrl'] = $facebook->getLoginUrl(
                    array(
                        'scope'         => 'public_profile,email',
                        'redirect_uri'  => $fbconfig['baseurl'],
                        'display' => 'popup' 
                    )
                );
} else {  
     try{
         //Assume that the user is logged in and authenticated
         $accessToken = $facebook->getAccessToken();
         $userProfile = $facebook->api("/".$user . '?fields=id,name,email,first_name,last_name,gender,birthday'); 
         dsp_add_new_user($userProfile);
        
        //logout url
         $logoutUrl = $facebook->getLogoutUrl(array('next' => $siteUrl . "/logout.php"));
        } catch(FacebookApiException $e){
         error_log($e);
         $user = NULL;
        }
 }
 
 if( $user ){   
     if( isset($_POST['status']) ){
        try{
            $status = $facebook->api('/me/feed', 'post', array('message' => $_POST['status']));
        } catch(FacebookApiException $e){
            error_log($e);
        }
     }
     
     if( isset($_POST['publish']) ){         
         try{
             $publish = $facebook->api("/me/feed", 'post', array(
                                    'message' => "Auto Status Update Check - Facebook PHP-SDK",
                                    'link' => $siteUrl,
                                    'picture' => $siteUrl,
                                    'name' => "",
                                    'caption' => "",
                                    'description' => "Checking Auto Status Update using Facebook PHP-SDK"                                    
                        ));
             
            } catch(FacebookApiException $e){
             echo $e->getMessage();
             error_log($e);
            }
     }
 }

if (isset($_REQUEST['state']) && isset($_REQUEST['code']) && $user != 0) { 
    echo "<script> 
            window.close();
            window.opener.location.href = '".site_url()."/members/';
        </script>";
}
