<?php 

if(is_user_logged_in()){
    $post_format = '';
    if(isset ($_GET['post']) && is_numeric($_GET['post'])){
        $post_id = $_GET['post'];
		
		$the_source = '';
		$source_meta = _core::method('_meta','get', $post_id , 'posts-settings' , 'source' );
		if( isset($source_meta['post_source']) && ( trim($source_meta['post_source'] != '' ) ) ){
			$the_source = $source_meta;
		}
					
        $post_edit = get_post($post_id);
        
		$post_categories = wp_get_post_categories( $post_id );	
        switch(get_post_format( $post_id )){
            case 'video':
                $post_format = 'video';
                $action_edit_video = true;    
                break;
            case 'audio':
                $post_format = 'audio';
                $action_edit_audio = true;
                break;
            case 'link':
                $post_format = 'link';
                $action_edit_link = true;
                break;
            case 'image':
                $post_format = 'image';
                $action_edit_image = true;
				
                break;
            default:
                $post_format = 'default';
                $action_edit_text = true;
                
            
        }
        
		if(has_post_thumbnail( $post_id )){
			$thumb_id = get_post_thumbnail_id($post_id);
		}
        
    }
	_core::method('_CosmoUploader','init');
	$front_end_disabled=!(_core::method('_settings','logic','settings','general', 'upload' , 'enb_image' )  ||
						  _core::method('_settings','logic','settings','general', 'upload' , 'enb_video' )  ||
						  _core::method('_settings','logic','settings','general', 'upload' , 'enb_text' ) 	||
						  _core::method('_settings','logic','settings','general', 'upload' , 'enb_audio' ) 	||
						  _core::method('_settings','logic','settings','general', 'upload' , 'enb_file' )
						 );
	if($front_end_disabled) echo '<h1 class="entry-title archive">'._e( 'Front-end submission is disabled.' , _DEV_ ).'</h1>';
?>

<div class="cosmo-box error medium hidden" id="video_error_msg_box">
	<span class="cosmo-ico"></span> 
	<span id="video_error_msg" ></span> 
</div>
<div class="cosmo-tabs submit" id="d39">
    <?php if(!isset($post_id)) { ?>    
	<ul class="tabs-nav"> 
		<?php if( _core::method('_settings','logic','settings','general', 'upload' , 'enb_image' ) ) {	?>
		<li class="first image tabs-selected"><a href="#pic_upload"><span><?php _e('Image',_DEV_); ?></span></a></li>
		<?php } ?> 
		<?php if(  _core::method('_settings','logic','settings','general', 'upload' , 'enb_video' ) ){	?>
        <li class="video <?php if( isset($post_id) && $post_format =='video'){echo 'first tabs-selected'; } ?>"> <a href="#video_upload"><span><?php _e('Video',_DEV_); ?></span></a></li>
		<?php } ?> 
		<?php if(  _core::method('_settings','logic','settings','general', 'upload' , 'enb_text' ) && !isset($post_id)  ){	?>
		<li class="text <?php if( isset($post_id) && $post_format =='standard'){echo 'first tabs-selected'; } ?>"> <a href="#text_post"><span><?php _e('Text',_DEV_); ?></span></a></li>
		<?php } ?> 
		<?php if(  _core::method('_settings','logic','settings','general', 'upload' , 'enb_audio' ) && !isset($post_id)  ){	?>
		<li class="audio <?php if( isset($post_id) && $post_format =='audio'){echo 'first tabs-selected'; } ?>"> <a href="#audio_post"><span><?php _e('Audio',_DEV_); ?></span></a></li>
		<?php } ?>
		<?php if(  _core::method('_settings','logic','settings','general', 'upload' , 'enb_file' ) && !isset($post_id)  ){	?>
		<li class="attach <?php if( isset($post_id) && $post_format =='link'){echo 'first tabs-selected'; } ?>"> <a href="#file_post"><span><?php _e('File',_DEV_); ?></span></a></li>
		<?php } ?> 
	</ul>
    <?php } ?>
	<?php if( ( _core::method('_settings','logic','settings','general', 'upload' , 'enb_image' ) && !isset($post_id) ) || ( isset($post_id) && $post_format == 'image')  ){	?>
	<div class="tabs-container" id="pic_upload">
			<h3><?php if( isset($post_id) && $post_format == 'image'){ _e('Edit picture',_DEV_); }else{ _e('Add picture',_DEV_); } ?></h3>
			<?php _core::method('_CosmoUploader','print_form',"Attached images","image",true,true)?>
			<form method="post" action="/post-item?phase=post" id="form_post_image">
			  <input type="hidden" value="" name="feat_image_id" id="feat_img_upload"  class="generic-record generic-single-record " />
			  
			<div class="field">
				<label>
					<h4><?php _e('Title',_DEV_)?></h4>
					<input type="text" class="text tipped front_post_input" name="title" id="img_post_title"  value="<?php if(isset($action_edit_image)){echo $post_edit -> post_title; } ?>">
					<p class="info"  id="img_post_title_info">
						<span class="warning" style="display:none; " id="img_post_title_warning"></span>
						<?php _e('Be descriptive or interesting!',_DEV_); ?>
					</p>
					
				</label>
			</div>
			<div class="field">
				<h4><?php _e('Text content',_DEV_)?></h4>
				<?php
					if(class_exists('WP_Editor')){
						global $wp_editor;
						$media_bar = false; /* set to true to show the media bar */
						$settings = array(); /* additional settings, */
                        if(isset($action_edit_image)){
                            echo $wp_editor->editor($post_edit -> post_content, 'image_content', $settings, $media_bar);
                        }else{
                            echo $wp_editor->editor('', 'image_content', $settings, $media_bar);
                        }
					}else{
						if(isset($action_edit_image)){
							wp_editor($post_edit -> post_content,'image_content');
						}else{
							wp_editor('','image_content');
						}
						
					}	
				?>
			</div>
			<div class="field">
				<label>
					<h4><?php _e('Category',_DEV_)?></h4>
					<?php 
					if(isset($action_edit_image) && is_array($post_categories) && sizeof($post_categories) ){
						//$cat = get_category( $post_categories[0] );
                        $args = array(  'orderby'            => 'ID', 
								    'order'              => 'ASC',
								    'hide_empty'         => 0, 
                                    'selected'           => $post_categories[0],
								    'id'                 => 'img_post_cat',
							    );
                    }else{
                        $args = array(  'orderby'            => 'ID', 
								    'order'              => 'ASC',
								    'hide_empty'         => 0, 
								    'id'                 => 'img_post_cat',
							    );    
                    }
					
					wp_dropdown_categories( $args );		    
					?>
					
				</label>
			</div>
			<div class="field"> 
				<label>
					<h4><?php _e('Tags',_DEV_); ?> <span>(<?php _e('recommended',_DEV_); ?>)</span></h4> 
					<input id="photo_tag_input" type="text" class="text tag_input tipped front_post_input" name="tags" value="<?php if(isset($action_edit_image)){ echo _core::method('post','list_tags',$post_id); } ?>" placeholder="tag 1, tag 2, tag 3, tag 4, tag 5" autocomplete="off">
				</label>
				<p class="info"  id="photo_tag_input_info"><?php _e('Use comma to separate each tag. E.g. design, wtf, awesome.',_DEV_); ?></p>
			</div>

			<?php if(_core::method('_settings','logic','settings','blogging','posts','source')){ ?>
			<div class="field">
				<label>
					<h4><?php _e('Source',_DEV_)?></h4> 
					<input type="text" class="text tipped front_post_input" name="source" id="img_post_source"  value="<?php if(isset($action_edit_image)){ echo $the_source; } ?>">
				</label>
				<p class="info" id="image_source_input_info"><?php _e('Example: http://cosmothemes.com',_DEV_); ?></p>
			</div>
			<?php } ?>
		
			<input type="hidden" value="image"  name="post_format">
			<?php if(isset($post_id)) { ?>
			<input type="hidden" value="<?php echo $post_id; ?>"  name="post_id">
			<?php } ?>
			<div class="field button">
				<p class="button blue">
					<input type="button" id="submit_img_btn"  onclick="add_image_post()" value="<?php if(isset($post_id)){ _e('Update post',_DEV_); }else{ _e('Submit post',_DEV_); } ?>"/>
				</p>
			</div>	
		</form>
	</div>
	<?php } ?> 
	<?php if( ( _core::method('_settings','logic','settings','general', 'upload' , 'enb_video' ) && !isset($post_id) ) || ( isset($post_id) && $post_format =='video') ){	?>
	<div class="tabs-container tabs-hide" id="video_upload">
		

			<h3><?php if( isset($post_id) && $post_format == 'video'){ _e('Edit video',_DEV_); }else{ _e('Add video',_DEV_); } ?></h3>
		<?php _core::method('_CosmoUploader','print_form',"Attached video","video",true,true)?>
		<form method="post" action="/post-item?phase=post" id="form_post_video" >
			<div class="field">
				<label>
					<h4><?php _e('Title',_DEV_)?></h4>
					<input type="text" class="text tipped front_post_input" name="title" id="video_post_title"  value="<?php if(isset($action_edit_video)){echo $post_edit -> post_title; } ?>">
					<p class="info"  id="video_post_title_info">
						<span class="warning" style="display:none; " id="video_post_title_warning"></span>
						<?php _e('Be descriptive or interesting!',_DEV_); ?>
					</p>
					
				</label>
			</div>
			<div class="field">
				<label>
					<h4><?php _e('Category',_DEV_)?></h4>
					<?php 
					if(isset($action_edit_video) && is_array($post_categories) && sizeof($post_categories) ){
						
                        $args = array(  'orderby'            => 'ID', 
								    'order'              => 'ASC',
								    'hide_empty'         => 0, 
                                    'selected'           => $post_categories[0],
								    'id'                 => 'video_post_cat',
							    );
                    }else{
                        $args = array(  'orderby'            => 'ID', 
								    'order'              => 'ASC',
								    'hide_empty'         => 0, 
								    'id'                 => 'video_post_cat',
							    );    
                    }			
					wp_dropdown_categories( $args );		    
					?>
					
				</label>
			</div>
			<div class="field">
				<h4><?php _e('Text content',_DEV_)?></h4>
				<?php
					if(class_exists('WP_Editor')){
						global $wp_editor;
						$media_bar = false; // set to true to show the media bar
						$settings = array(); // additional settings,
						
						if(isset($action_edit_video)){
                            echo $wp_editor->editor($post_edit -> post_content, 'video_content', $settings, $media_bar);
                        }else{
                            echo $wp_editor->editor('', 'video_content', $settings, $media_bar);
                        }
					}else{
						if(isset($action_edit_video)){
							wp_editor($post_edit -> post_content,'video_content');
						}else{
							wp_editor('','video_content');
						}
						
					}	
				?>
			</div>
			<div class="field">
				<label>
					<h4><?php _e('Tags',_DEV_); ?> <span>(<?php _e('recommended',_DEV_); ?>)</span></h4>
					<input id="video_tag_input" type="text" class="text tag_input tipped front_post_input" name="tags" value="<?php if(isset($action_edit_video)){ echo _core::method('post','list_tags',$post_id); } ?>" placeholder="tag 1, tag 2, tag 3, tag 4, tag 5" autocomplete="off">
				</label>
				<p class="info" id="video_tag_input_info"><?php _e('Use comma to separate each tag. E.g. design, wtf, awesome.',_DEV_); ?></p>
			</div>
			<?php if(_core::method('_settings','logic','settings','blogging','posts','source')){ ?>
			<div class="field">
				<label>
					<h4><?php _e('Source',_DEV_)?></h4>
					<input type="text" class="text tipped front_post_input" name="source" id="video_post_source"  value="<?php if(isset($action_edit_video)){ echo $the_source; } ?>">
				</label>
				<p class="info" id="video_source_input_info"><?php _e('Example: http://cosmothemes.com',_DEV_); ?></p>
			</div>
			<?php } ?>
			<input type="hidden" value="video"  name="post_format">
			<?php if(isset($post_id)) { ?>
                <input type="hidden" value="<?php echo $post_id; ?>"  name="post_id">
			<?php } ?>
			<div class="field button">
				<p class="button blue">
					<input type="button" id="submit_video_btn"  onclick="add_video_post()" value="<?php if(isset($post_id)){ _e('Update post',_DEV_); }else{ _e('Submit post',_DEV_); } ?>" />
				</p>
			</div>
		</form>
	</div>
	<?php } ?> 
	<?php if( ( _core::method('_settings','logic','settings','general', 'upload' , 'enb_text' ) && !isset($post_id) ) || ( isset($post_id) && $post_format == 'default') ){	?>
	<div class="tabs-container" id="text_post">
		<form method="post" action="/post-item?phase=post" id="form_post_text" >  
			<h3><?php if( isset($post_id) && $post_format == 'default'){ _e('Edit text',_DEV_); }else{ _e('Add text',_DEV_); } ?></h3>
			
			<div class="field">
				<label>
					<h4><?php _e('Title',_DEV_)?></h4>
					<input type="text" class="text tipped front_post_input" name="title" id="text_post_title"  value="<?php if(isset($action_edit_text)){echo $post_edit -> post_title; } ?>">
					<p class="info"  id="text_post_title_info">
						<span class="warning" style="display:none; " id="text_post_title_warning"></span>
						<?php _e('Be descriptive or interesting!',_DEV_); ?>
					</p>
					
				</label>
			</div>
			<div class="field">
				<h4><?php _e('Text content',_DEV_)?></h4>
				<?php
					if(class_exists('WP_Editor')){
						global $wp_editor;
						$media_bar = false; // set to true to show the media bar
						$settings = array(); // additional settings,
						
						if(isset($action_edit_text)){
                            echo $wp_editor->editor($post_edit -> post_content, 'text_content', $settings, $media_bar);
                        }else{
                            echo $wp_editor->editor('', 'text_content', $settings, $media_bar);
                        }
					}else{
						if(isset($action_edit_text)){
							wp_editor($post_edit -> post_content,'text_content');
						}else{
							wp_editor('','text_content');
						}
						
					}	
				?>
			</div>
			<div class="field">
				<label>
					<h4><?php _e('Category',_DEV_)?></h4>
					<?php 
					
					if(isset($action_edit_text) && is_array($post_categories) && sizeof($post_categories) ){
						
                        $args = array(  'orderby'            => 'ID', 
								    'order'              => 'ASC',
								    'hide_empty'         => 0, 
                                    'selected'           => $post_categories[0],
								    'id'                 => 'text_post_cat',
							    );
                    }else{
                        $args = array(  'orderby'            => 'ID', 
								    'order'              => 'ASC',
								    'hide_empty'         => 0, 
								    'id'                 => 'text_post_cat',
							    );    
                    }			
					wp_dropdown_categories( $args );		    
					?>
					
				</label>
			</div>
			<div class="field">
				<label>
					<h4><?php _e('Tags',_DEV_); ?> <span>(<?php _e('recommended',_DEV_); ?>)</span></h4>
					<input id="text_tag_input" type="text" class="text tag_input tipped front_post_input" name="tags" value="<?php if(isset($action_edit_text)){ echo _core::method('post','list_tags',$post_id); } ?>" placeholder="tag 1, tag 2, tag 3, tag 4, tag 5" autocomplete="off">
				</label>
				<p class="info"  id="text_tag_input_info"><?php _e('Use comma to separate each tag. E.g. design, wtf, awesome.',_DEV_); ?></p>
			</div>
			<?php if(_core::method('_settings','logic','settings','blogging','posts','source')){ ?>
			<div class="field">
				<label>
					<h4><?php _e('Source',_DEV_)?></h4>
					<input type="text" class="text tipped front_post_input" name="source" id="text_post_source"  value="<?php if(isset($action_edit_text)){ echo $the_source; } ?>">
				</label>
				<p class="info" id="text_source_input_info"><?php _e('Example: http://cosmothemes.com',_DEV_); ?></p>
			</div>
			<?php } ?>
			<input type="hidden" value=""  name="post_format">
            <?php if(isset($post_id)) { ?>
                <input type="hidden" value="<?php echo $post_id; ?>"  name="post_id">
			<?php } ?>
			<div class="field button">
				<p class="button blue">
					<input type="button" id="submit_text_btn"  onclick="add_text_post()" value="<?php if(isset($post_id)){ _e('Update post',_DEV_); }else{ _e('Submit post',_DEV_); } ?>"/>
				</p>
			</div>		
		</form>
	</div>
	<?php } ?> 
	<?php if( ( _core::method('_settings','logic','settings','general', 'upload' , 'enb_audio' ) && !isset($post_id) ) || ( isset($post_id) && $post_format == 'audio') ){	?>

	<div class="tabs-container" id="audio_post">
		 
			<h3><?php if( isset($post_id) && $post_format == 'audio'){ _e('Edit audio file',_DEV_); }else{ _e('Add mp3 audio file',_DEV_); } ?></h3>
			
			<?php _core::method('_CosmoUploader','print_form',"Attached audio","audio",false,false);
				  _core::method('_CosmoUploader','print_feat_img_form',"audio")?>
	
		  <form method="post" action="/post-item?phase=post" id="form_post_audio" > 
				
			<div class="field">
				<label>
					<h4><?php _e('Title',_DEV_)?></h4>
					<input type="text" class="text tipped front_post_input" name="title" id="audio_post_title"  value="<?php if(isset($action_edit_audio)){echo $post_edit -> post_title; } ?>">
					<p class="info"  id="audio_img_post_title_info">
						<span class="warning" style="display:none; " id="audio_img_post_title_warning"></span>
						<?php _e('Be descriptive or interesting!',_DEV_); ?>
					</p>
					
				</label>
			</div>
			
			
			
			<div class="field">
				<h4><?php _e('Text content',_DEV_)?></h4>
				<?php
					if(class_exists('WP_Editor')){
						global $wp_editor;
						$media_bar = false; // set to true to show the media bar
						$settings = array(); // additional settings,
						
						if(isset($action_edit_audio)){
                            echo $wp_editor->editor($post_edit -> post_content, 'audio_content', $settings, $media_bar);
                        }else{
                            echo $wp_editor->editor('', 'audio_content', $settings, $media_bar);
                        }
					}else{
						if(isset($action_edit_audio)){
							wp_editor($post_edit -> post_content,'audio_content');
						}else{
							wp_editor('','audio_content');
						}
						
					}	
				?>
			</div>
			<div class="field">
				<label>
					<h4><?php _e('Category',_DEV_)?></h4>
					<?php 
					
					if(isset($action_edit_audio) && is_array($post_categories) && sizeof($post_categories) ){
						
                        $args = array(  'orderby'            => 'ID', 
								    'order'              => 'ASC',
								    'hide_empty'         => 0, 
                                    'selected'           => $post_categories[0],
								    'id'                 => 'audio_post_cat',
							    );
                    }else{
                        $args = array(  'orderby'            => 'ID', 
								    'order'              => 'ASC',
								    'hide_empty'         => 0, 
								    'id'                 => 'audio_post_cat',
							    );   
                    }				
					wp_dropdown_categories( $args );		    
					?>
					
				</label>
			</div>
			<div class="field">
				<label>
					<h4><?php _e('Tags',_DEV_); ?> <span>(<?php _e('recommended',_DEV_); ?>)</span></h4>
					<input id="audio_photo_tag_input" type="text" class="text tag_input tipped front_post_input" name="tags" value="<?php if(isset($action_edit_audio)){ echo _core::method('post','list_tags',$post_id); } ?>" placeholder="tag 1, tag 2, tag 3, tag 4, tag 5" autocomplete="off">
				</label>
				<p class="info"  id="audio_photo_tag_input_info"><?php _e('Use comma to separate each tag. E.g. design, wtf, awesome.',_DEV_); ?></p>
			</div>
			<?php if(_core::method('_settings','logic','settings','blogging','posts','source')){ ?>
			<div class="field">
				<label>
					<h4><?php _e('Source',_DEV_)?></h4>
					<input type="text" class="text tipped front_post_input" name="source" id="audio_img_post_source" value="<?php if(isset($action_edit_audio)){ echo $the_source; } ?>">
				</label>
				<p class="info" id="audio_image_source_input_info"><?php _e('Example: http://cosmothemes.com',_DEV_); ?></p>
			</div>
			<?php } ?>
			
			<input type="hidden" value="audio"  name="post_format">
            <?php if(isset($post_id)) { ?>
                <input type="hidden" value="<?php echo $post_id; ?>"  name="post_id">
			<?php } ?>
			<div class="field button">
				<p class="button blue">
					<input type="button" id="submit_audio_btn"  onclick="add_audio_post()" value="<?php if(isset($post_id)){ _e('Update post',_DEV_); }else{ _e('Submit post',_DEV_); } ?>"/>
				</p>
			</div>		
		</form>
	</div>
	<?php } ?> 
	<?php if( (_core::method('_settings','logic','settings','general', 'upload' , 'enb_file' ) && !isset($post_id) ) || ( isset($post_id) && $post_format == 'link') ){	?>
	<div class="tabs-container" id="file_post">
		
			<h3><?php if( isset($post_id) && $post_format == 'link'){ _e('Edit file',_DEV_); }else{ _e('Add file',_DEV_); } ?></h3>
			
			<?php _core::method('_CosmoUploader','print_form',"Attached files","link",false,false);
				  _core::method('_CosmoUploader','print_feat_img_form',"link")?>
		<form method="post" action="/post-item?phase=post" id="form_post_file" >  
			<div class="field">
				<label>
					<h4><?php _e('Title',_DEV_)?></h4>
					<input type="text" class="text tipped front_post_input" name="title" id="file_post_title" value="<?php if(isset($action_edit_link)){echo $post_edit -> post_title; } ?>">
					<p class="info"  id="file_img_post_title_info">
						<span class="warning" style="display:none; " id="file_img_post_title_warning"></span>
						<?php _e('Be descriptive or interesting!',_DEV_); ?>
					</p>
					
				</label>
			</div>
			
			
			
			<div class="field">
				<h4><?php _e('Text content',_DEV_)?></h4>
				<?php
					if(class_exists('WP_Editor')){
						global $wp_editor;
						$media_bar = false; // set to true to show the media bar
						$settings = array(); // additional settings,
						
						if(isset($action_edit_link)){
                            echo $wp_editor->editor($post_edit -> post_content, 'file_content', $settings, $media_bar);
                        }else{
                            echo $wp_editor->editor('', 'file_content', $settings, $media_bar);
                        }
					}else{
						if(isset($action_edit_link)){
							wp_editor($post_edit -> post_content,'file_content');
						}else{
							wp_editor('','file_content');
						}
						
					}	
				?>
			</div>
			<div class="field">
				<label>
					<h4><?php _e('Category',_DEV_)?></h4>
					<?php 
					
					
								
					if(isset($action_edit_link) && is_array($post_categories) && sizeof($post_categories) ){
						
                        $args = array(  'orderby'            => 'ID', 
								    'order'              => 'ASC',
								    'hide_empty'         => 0, 
                                    'selected'           => $post_categories[0],
								    'id'                 => 'file_post_cat',
							    );
                    }else{
                        $args = array(  'orderby'            => 'ID', 
								    'order'              => 'ASC',
								    'hide_empty'         => 0, 
								    'id'                 => 'file_post_cat',
							    );  
                    }				
					wp_dropdown_categories( $args );		    
					?>
					
				</label>
			</div>
			<div class="field">
				<label>
					<h4><?php _e('Tags',_DEV_); ?> <span>(<?php _e('recommended',_DEV_); ?>)</span></h4>
					<input id="file_photo_tag_input" type="text" class="text tag_input tipped front_post_input" name="tags" value="<?php if(isset($action_edit_link)){ echo _core::method('post','list_tags',$post_id); } ?>" placeholder="tag 1, tag 2, tag 3, tag 4, tag 5" autocomplete="off">
				</label>
				<p class="info"  id="file_photo_tag_input_info"><?php _e('Use comma to separate each tag. E.g. design, wtf, awesome.',_DEV_); ?></p>
			</div>
			<?php if(_core::method('_settings','logic','settings','blogging','posts','source')){ ?>
			<div class="field">
				<label>
					<h4><?php _e('Source',_DEV_)?></h4>
					<input type="text" class="text tipped front_post_input" name="source" id="file_img_post_source"  value="<?php if(isset($action_edit_link)){ echo $the_source; } ?>">
				</label>
				<p class="info" id="file_image_source_input_info"><?php _e('Example: http://cosmothemes.com',_DEV_); ?></p>
			</div>
			<?php } ?>
			<?php if(isset($post_id)) { ?>
                <input type="hidden" value="<?php echo $post_id; ?>"  name="post_id">
			<?php } ?>
			<input type="hidden" value="link"  name="post_format">
			<div class="field button">
				<p class="button blue">
					<input type="button" id="submit_file_btn"  onclick="add_file_post()" value="<?php if(isset($post_id)){ _e('Update post',_DEV_); }else{ _e('Submit post',_DEV_); } ?>"/>
				</p>
			</div>		
		</form>
	</div>
	<?php } ?> 
	
</div>
<div id="not_logged_msg" style="display:none"><?php _e('You must be logged in to submit an post',_DEV_); ?></div>
<div id="success_msg" style="display:none"></div>
<div id="loading_" style="display:none"><object width="100" height="100" type="application/x-shockwave-flash" data="<?php echo get_template_directory_uri() ?>/images/preloader.swf" id="ajax-indicator-swf" style="visibility: visible;">
				  <param name="quality" value="high"><param name="allowscriptaccess" value="always">
				  <param name="wmode" value="transparent">
				  <param name="scale" value="noborder">
				</object></div>
<?php 
}else{
	$login_page=_core::method('_settings','get','settings','general','theme','login-page');
	echo __('You must be',_DEV_).' <a href="'.get_permalink($login_page).'" class="link">'.__('logged in',_DEV_).'</a>'.__('to submit a post.',_DEV_);
}
?>