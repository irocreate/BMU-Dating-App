<?php 

/*
 * We start by doing a query to retrieve all users
 * We need a total user count so that we can calculate how many pages there are
 */
$users = '';
$request_url = get_bloginfo('url') . "/wp-admin/admin.php?page=dsp-admin-sub-page2";
$msg = '';
include_once('functions/dsp_user_list_function.php');
//if(isset($_POST['search'])){ 
	$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
	$endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
	//$startDate = "2014-08-21";
	//$endDate = "2014-10-21";
	//var_dump($endDate);die;
	if(!empty($startDate) && !empty($endDate))
	{
		$page = isset($_GET['p'])?$_GET['p']:1;
        $users_per_page = 5;
		$total_users = dsp_get_total_users($startDate,$endDate);
		$total_pages =1 ;
		$start = $users_per_page * ($page - 1);
		

		$users = dsp_searchByDate($startDate,$endDate,$start,$users_per_page);

	}else{
		if(isset($_GET['search'])){
				$msg = '<p><?php echo language_code(\'DSP_DATEFIELD_EMPTY\');?></p>';
		}
	}
//}

?>
<div class="wrap">
<h2><?php echo language_code('DSP_BLACKLIST_SEARCH_USERS');?></h2>

<?php if(!empty($msg)){ ?>
  	<div class="error">
	<?php  	echo $msg; ?>
  </div>
<?php } ?>

<form method ="get" name="searchByDate" action="" class="report-userlists-report">
	<input name="page" type="hidden" value="dsp-admin-sub-page4"/>
	<input name="pid" type="hidden" value="Userlists"/>
	<div class="dsp_membership_col2"><input name="startDate"  placeholder = "<?php echo language_code('DSP_START_DATE'); ?>" class="datepicker-control" id ="startDate" type="text" value="<?php if(isset($startDate)){echo $startDate;}else{echo language_code('DSP_START_DATE');}?>" /></div>
	<div class="dsp_membership_col2"><input name="endDate" placeholder = "<?php echo language_code('DSP_END_DATE'); ?>" class="datepicker-control" id ="endDate" type="text" value="<?php if(isset($endDate)){echo $endDate;}else{echo language_code('DSP_END_DATE');}?>" /></div>
    <div class="dsp_membership_col3"><input type="submit" name="search" class="button"  value="Search"/></div>
   <div class="dsp_clr"></div>
</form>
<?php if(!empty($users)):
          $total_pages = ceil($total_users / $users_per_page); 
?>
		<table cellpadding="0" cellspacing="0" border="0"  class="widefat">
	        <tr>
	            <th scope="col" class="manage-column"><?php echo language_code('DSP_TITLE_USERNAME') ?></th>
	            <th scope="col" class="manage-column"><?php echo language_code('DSP_TITLE_EMAIL_ADDESS') ?></th> 
	            <th scope="col" class="manage-column"><?php echo language_code('DSP_TITLE_REGISTRATION_DATE') ?></th>
	       </tr>
		    <?php foreach ($users as  $user): 
		    			$profile_id = dsp_get_profile_id_by_userId($user->ID);
		    ?>
	          	 <tr>
	     	        <td class="dsp_admin_headings2">
		             <a href="<?php echo add_query_arg(array('pid' => 'media_profile_view',
	                                'mode' => 'edit', 'profile_id' => $profile_id),$request_url);?>"><?php echo  $user->user_login;?></a>
		        	</td>
		        	<td class="dsp_admin_headings2">
		             <?php echo $user->user_email;?>
		        	</td>
		        	<td class="dsp_admin_headings2">
		             <?php
		             		$createDate = new DateTime($user->user_registered);
							echo  $createDate->format('Y-m-d');
		              ?>
		        	</td>
		        </tr> 
		      <?php endforeach; ?>  
        
		</table>       

<?php 
  // grab the current query parameters
$query_string = $_SERVER['QUERY_STRING'];

// The $base variable stores the complete URL to our page, including the current page arg

// if in the admin, your base should be the admin URL + your page
$base = admin_url('admin.php') . '?' . remove_query_arg('p', $query_string) . '%_%';

// if on the front end, your base is the current page
//$base = get_permalink( get_the_ID() ) . '?' . remove_query_arg('p', $query_string) . '%_%';

echo paginate_links( array(
    'base' => $base, // the base URL, including query arg
    'format' => '&p=%#%', // this defines the query parameter that will be used, in this case "p"
    'prev_text' => __('&laquo; Previous'), // text for previous page
    'next_text' => __('Next &raquo;'), // text for next page
    'total' => $total_pages, // the total number of pages we have
    'current' => $page, // the current page
    'end_size' => 1,
    'mid_size' => 5,
));
?>
<?php endif; ?>
</div>