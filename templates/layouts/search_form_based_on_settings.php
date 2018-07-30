<?php
	//Criteria to be include  search form2  layout when it is  template2/template7
	$criteria = array(2,7);
	$includeSearchForm2 = (in_array($templateNumber,$criteria)) ? true : false;
	
	if($searchFormSettings == 'nw' ): 
	    	$includeSearchForm2 ? 
	    	include_once(WP_DSP_ABSPATH . 'templates/layouts/search_form2.php') : 
	    	include_once(WP_DSP_ABSPATH . 'templates/layouts/search_form.php');
       
    else :
        include_once(WP_DSP_ABSPATH . 'templates/layouts/old_search_form.php');
    endif;
     
