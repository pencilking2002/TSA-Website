<?php 

/*

Template Name: members

*/

?>
	<?php get_header(); ?>
	
	<div class="b_content clearfix" id="main">
		<div class="b_page events">

			
				<?php 
				$wp_user_search = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY ID");
				foreach ( $wp_user_search as $userid ) {
					$user_id       = (int) $userid->ID;
					$user_login    = stripslashes($userid->user_login);
					$display_name  = stripslashes($userid->display_name);
					$user_email 	= $userid->user_email;
					$return  = '';
					$return .= "\t" . '<li>'. $display_name .'</li>' . "\n";
					$return .= "\t" . '<li>'. $user_email .'</li>' . "\n";
					print($return);
				}
				?>
				
					<li id="users">
					 <h2><?php _e('users:'); ?></h2>
					   <form action="<?php bloginfo('url'); ?>" method="get">
					   <?php wp_dropdown_users(array('name' => 'author')); ?>
					   <input type="submit" name="submit" value="view" />
					   </form>
					</li>
				
			
			
			
		
			<?php 	/*
			$user_info=get_user_by_email($author);
			echo ('Username:'.$user_info->user_login . '\n');
			      echo('User level: ' . $user_info->user_level . '\n');
			      echo('User ID: ' . $user_info->ID . '\n');
		*/	?>
			
			

</div>
</div>

<?php get_footer(); ?>