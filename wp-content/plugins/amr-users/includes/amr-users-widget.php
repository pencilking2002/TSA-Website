<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
/*
Description: Display a sweet, concise list of events from users sources, using a list type from the amr users plugin <a href="options-general.php?page=manage_amr_users">Manage Settings Page</a> and  <a href="widgets.php">Manage Widget</a> 

*/
class amr_users_widget extends WP_widget {
    /** constructor */
    function amr_users_widget() {
		$widget_ops = array ('description'=>__('Users', 'amr-users-events-list' ),'classname'=>__('users', 'amr-users' ));
        $this->WP_Widget(false, __('User list', 'amr-users-list' ), $widget_ops);	
    }
	
/* ============================================================================================== */	
	function widget ($args, $instance) { /* this is the piece that actualy does the widget display */

		extract ($args, EXTR_SKIP); /* this is for the before / after widget etc*/
	extract ($instance, EXTR_SKIP); /* title list */	

	//output...
	echo $before_widget;
	echo $before_title . $title . $after_title ;
	
	echo amr_userlist(array('list'=>$list,
	'show_headings'=>false,
	'show_search'=> false,
	'show_perpage' => false,
	'show_csv' => false
	));

	
	echo $after_widget; 

	}
/* ============================================================================================== */	
	
	function update($new_instance, $old_instance) {  /* this does the update / save */

		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if (!empty($new_instance['list'])) $instance['list'] = strip_tags($new_instance['list']);
		else $instance['list'] = '1';
		return $instance;

	}
	
	
/* ============================================================================================== */
	function form($instance) { /* this does the display form */
	
        $instance = wp_parse_args( (array) $instance, array( 
			'title' => __('Users','amr-users-list'),
			'list'=>'1',
			'showsearch'=> false,
			'showperpage'=> false,
			'max' => 50			));
			
		$title = $instance['title'];	
		$list = $instance['list'];
		$max = $instance['max'];
	
?>
	<input type="hidden" id="submit" name="submit" value="1" />
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'amr-users-events-list'); ?> 
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" 
	value="<?php echo esc_attr($title); ?>" />		</label></p>

	<p><label for="<?php echo $this->get_field_id('list'); ?>"><?php _e('User List', 'amr-users'); ?> 
	<input id="<?php echo $this->get_field_id('list'); ?>" name="<?php echo $this->get_field_name('list'); ?>" type="text" 
	value="<?php echo esc_attr($list); ?>" /></label></p>
	
<?php }
/* ============================================================================================== */
}


?>