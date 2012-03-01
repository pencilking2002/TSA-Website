<?php
global $current_user, $wp_roles;
get_currentuserinfo();
_core::method( "_CosmoUploader" , "init_for_floating_form" );
?>
<script type="">
</script>
<div class="entry-content entry">
	<?php the_content(); 

	  if ( isset($_REQUEST['error']) && $_REQUEST['error'] == 'true' ){ 
		  $error = __('The passwords you entered do not match.  Your password was not updated.', _DEV_ );
	  }elseif( isset($_REQUEST['success']) && $_REQUEST['success'] == 'true' ){
		  $success = __('Your profile info was updated.', _DEV_ );
	  }
	  
	  
	  if ( $error ) echo '<p class="error">' . $error . '</p>'; 
	  if ( isset($success) ) echo '<p class="success">' . $success . '</p>'; 

	?>


	<?php if ( !is_user_logged_in() ) : ?>
			<p class="warning">
				<?php _e('You must be logged in to edit your profile.', _DEV_); ?>
			</p><!-- .warning -->
	<?php else : ?>
		<?php 
			
				
		?>
		<form method="post" id="adduser" action="<?php the_permalink(); ?>">
			<div class="tabs-container form-profile-pic">
				<?php



					$action['group'] = 'avatar';
					$action['topic'] = 'upload';
					$action['index'] = '';
					$action['upload_url'] =  home_url().'/wp-admin/media-upload.php?post_id=-1&amp;type=image&amp;TB_iframe=true';

					$custom_avatar_url = '';	
					$custom_avatar_meta =  get_user_meta($current_user->ID, 'custom_avatar', true);
					if(is_numeric($custom_avatar_meta) && $custom_avatar_meta > 0){
						$custom_avatar_url = wp_get_attachment_url($custom_avatar_meta);

					}

				?>
				<label for="profile-pic"><?php _e('Profile picture', _DEV_ ); ?></label>
				<?php
					echo cosmo_avatar( $current_user->ID , 100 , $default = DEFAULT_AVATAR_100 );

					if( defined( 'IS_FOR_DEMO' ) && get_the_author_meta( 'user_login', $current_user -> ID ) == 'demo'){
						$disabled = 'disabled';
					}else{
						$disabled = '';
					}
				?>
				<span><?php echo __( 'Click on picture to change it.' , _DEV_ ); ?></span>
				<label class="remove-avatar" for="remove_avatar"><input type="checkbox" name="remove_avatar" <?php echo $disabled ?> value="1" id="remove_avatar"> <?php _e('Remove avatar', _DEV_ ); ?></label>
				<input type="hidden" name="avatar_id" id="avatar_upload_id" value="<?php echo $custom_avatar_meta; ?>"  class="generic-record generic-single-record " />
				<script type="text/javascript">
					Cosmo_Uploader.Get_Floating_Uploader(".form-profile-pic img","#avatar_upload_id");
				</script>

			</div><!-- .profile pic -->  
			<div class="tabs-container form-username">
				<label for="user-name"><?php _e( 'Username' , _DEV_ ); ?></label>
				<input class="text-input" name="user-name" type="text" id="user-name" disabled value="<?php the_author_meta( 'user_login', $current_user->ID ); ?>" />
			</div><!-- .form-username -->
			<div class="tabs-container form-username">
				<label for="first-name"><?php _e( 'First name' , _DEV_ ); ?></label>
				<input class="text-input" name="first-name" type="text" id="first-name" <?php echo $disabled ?> value="<?php echo get_user_meta( $current_user->ID, 'first_name', true ); ?>" />
			</div><!-- .form-username -->
			<div class="tabs-container form-username">
				<label for="last-name"><?php _e( 'Last name' , _DEV_ ); ?></label>
				<input class="text-input" name="last-name" type="text" id="last-name" <?php echo $disabled ?> value="<?php echo get_user_meta( $current_user->ID, 'last_name',  true ); ?>" />
			</div><!-- .form-username -->
			<div class="tabs-container form-email">
				<label for="email"><?php _e('E-mail *', _DEV_); ?></label>
				<input class="text-input" name="email" type="text" id="email" <?php echo $disabled ?> value="<?php echo get_user_meta( $current_user->ID, 'user_email', true ); ?>" />
			</div><!-- .form-email -->
			<div class="tabs-container form-url">
				<label for="url"><?php _e( 'Website' , _DEV_ ); ?></label>
				<input class="text-input" name="url" type="text" id="url" <?php echo $disabled ?> value="<?php echo get_user_meta( $current_user->ID, 'user_url', true ); ?>" />
			</div><!-- .form-url -->
			<div class="tabs-container form-password">
				<label for="pass1"><?php _e( 'Password *' , _DEV_ ); ?> </label>
				<input class="text-input" name="pass1" <?php echo $disabled ?> type="password" id="pass1" />
			</div><!-- .form-password -->
			<div class="tabs-container form-password">
				<label for="pass2"><?php _e( 'Repeat password *' , _DEV_ ); ?></label>
				<input class="text-input" name="pass2" <?php echo $disabled ?> type="password" id="pass2" />
			</div><!-- .form-password -->
			<div class="tabs-container form-textarea">
				<label for="description"><?php _e( 'Biographical information' , _DEV_ ) ?></label>
				<textarea name="description" id="description" <?php echo $disabled ?> rows="3" cols="50"><?php echo get_user_meta( $current_user->ID, 'description',  true ); ?></textarea>
			</div><!-- .form-textarea -->
			<p class="form-submit">
				<?php //echo $referer; ?>
				<input name="updateuser" type="submit" id="updateuser" <?php echo $disabled ?> class="submit button" value="<?php _e('Update', _DEV_); ?>" />
				<?php wp_nonce_field( 'update-user' ) ?>
				<input name="action" type="hidden" id="action" <?php echo $disabled ?> value="update-user" />
			</p><!-- .form-submit -->
		</form><!-- #adduser -->
		<?php endif; ?>
	</div><!-- .entry-content -->
