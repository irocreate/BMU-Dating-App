<?php
/*
=================================================
Widget That helps users to carasaul their contents
on front page or any pages that they want.
=================================================
*/

class dsp_dating_search_widget extends WP_Widget {

	/**
	 * Specifies the widget name, description, class name and instatiates it
	 */
	public function __construct() {
		parent::__construct( 
			'dsp_dating_seach_widget',
			__( 'DSP Dating Search Widget', 'dsp_dating' ),
			array(
				'classname'   => 'dsp_dating_seach_widget',
				'description' => __( 'A widget that displays the user search form.', 'dsp_dating' )
			) 
		);
	}


	/**
	 * Generates the back-end layout for the widget
	 */

	public function form( $instance ) {
		// Default widget settings
		$members_carousel = array(
			'title'               => 'Search',
		);

		$instance = wp_parse_args( (array) $instance, $members_carousel);

		// The widget content ?>
		<!-- Title -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'lavish' ); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>
		<?php
	}


	/**
	 * Processes the widget's values
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		// Update values
		$instance['title']               = strip_tags( stripslashes( $new_instance['title'] ) );
		return $instance;
	}


	/**
	 * Output the contents of the widget
	 */
	public function widget( $args, $instance ) {
		// Extract the arguments
		extract( $args );
                if(!isset($instance['title']))
                {
                    $instance['title'] = '';
                }
		$title              = apply_filters( 'widget_title', $instance['title'] );
	

		// Display the markup before the widget (as defined in functions.php)
		echo $before_widget;
		if ($title) {
			echo '<h2 style="text-align:center; margin-bottom:30px;">'.$title.'</h2>';
		}
		?>
		<div class="lavish-seachbox">
        	<?php do_action('wpdating_search_form'); ?>
        </div>
        <?php 
		// Display the markup after the widget (as defined in functions.php)
		echo $after_widget;
	}
}

// Register the widget using an annonymous function
function dsp_dating_search(){
    register_widget("dsp_dating_search_widget");
}
add_action('widgets_init', 'dsp_dating_search');
?>