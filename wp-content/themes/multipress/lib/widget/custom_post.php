<?php
    class widget_custom_post extends WP_Widget {

        function widget_custom_post() {
            $widget_ops = array( 'classname' => 'widget_custom_post' , 'description' => __( " Posts' list" , _DEV_ ) . ' <span class="cosmo-widget-red">( ' . __( 'recommend to use "Mainpage content" sidebar' , _DEV_ ) . ' )</span>' );
            $this -> WP_Widget( 'widget_custom_post' , _CT_ . ': ' . __( " Posts' list" , _DEV_ ) , $widget_ops );
        }

        function widget( $args , $instance ) {

            /* prints the widget*/
            extract($args, EXTR_SKIP);

            if( isset( $instance['title'] ) ){
                $title = $instance['title'];
            }else{
                $title = '';
            }

			if( isset( $instance['nr_posts'] ) && is_numeric($instance['nr_posts']) ){
                $nr_posts = $instance['nr_posts'];
            }else{
                $nr_posts = 3;
            }
			
			if( isset( $instance['post_view'] ) ){
                $post_view = $instance['post_view'];
            }else{
                $post_view = 'list';
            }
			
			if( isset( $instance['light_box'] ) ){
                $light_box = $instance['light_box'];
            }else{
                $light_box = 1;
            }
			if($light_box == ''){ $light_box = 0;}
			
			if(isset($instance['customPost']) ){
				$custompost		= $instance['customPost'];
			}else{
				$custompost		= array();
			}
			
            echo $before_widget;
		?>
			<div class="w_<?php echo _core::method( '_layout' , 'length' , 0 , 'front_page', 'content'  ). ' '. $post_view; ?>-view">
			
			
		<?php
            if( !empty( $title ) ){
				echo $before_title . $title . $after_title;
			}	
			
			/*generate a random ID for the container*/
			$container_id = mt_rand(0,999999);
			
			echo '<div class="cp_title fl" id="cp_posts_'.$container_id.'" ></div>';
			echo '<p class="delimiter">&nbsp;</p>';
				
			
        ?>
				<!-- panel tags -->
				
				<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.mosaic.1.0.1.min.js" type="text/javascript" ></script>
				<div class="www loop-container-view <?php echo $post_view; ?>" id="posts_<?php echo $container_id; ?>">
					<!-- <div class="element last"> -->
						<?php
							if(sizeof($custompost)){
						?>	
								<script type="text/javascript">
								jQuery(document).ready(function () {
									var custom_posts = new Array();	
								
						<?php	$i = 0;		
								foreach($custompost as $c_p){ 
									if(post_type_exists($c_p)){?>
										custom_posts[<?php echo $i; ?>] = '<?php echo $c_p; ?>';
						<?php 	
										$i++; 
									} 
								} 
								?>
									get_c_post(custom_posts, custom_posts[0] ,<?php echo $nr_posts; ?>,'<?php echo $post_view; ?>',<?php echo $light_box; ?>,'posts_<?php echo $container_id; ?>');
								});
								</script>
						<?php		
							}else{
								_e('Please select at least one post type',_DEV_);
							}
						?>
					<!-- </div> -->
				</div>
			</div>
        <?php
            echo $after_widget;
			$widgets = wp_get_sidebars_widgets();
			if(sizeof($widgets) && sizeof($widgets['front'])){
				/*if it is not the last widget in the front sidebar, we output the delimiter*/
				if($this->id != $widgets['front'][sizeof($widgets['front'])-1]){
					echo '<div class="delimiter blank">&nbsp;</div>';
				}
			}
		}
		
		
        function update( $new_instance, $old_instance) {

            /*save the widget*/
            $instance = $old_instance;
			print_r($old_instance);
			
            $instance['title']              = strip_tags( $new_instance['title'] );
			$instance['nr_posts']        	= strip_tags( $new_instance['nr_posts'] );
			$instance['post_view']        	= strip_tags( $new_instance['post_view'] );
			$instance['light_box']        	= strip_tags( $new_instance['light_box'] );
			
			$instance['customPost'] = array();
			foreach($new_instance['customPost'] as $cust_post){
				if($cust_post != ''){
					$instance['customPost'][] = $cust_post;
				}	
			}
			
			return $instance;
        }

        function form($instance) {

            /* widget form in backend */
            $instance       = wp_parse_args( (array) $instance, array( 'title' => '' , 'nr_posts' => '', 'post_view' => 'list', 'light_box' => 1, 'customPost' => array() ) );
            $title          = strip_tags( $instance['title'] );
			$nr_posts    	= strip_tags( $instance['nr_posts'] );
			$post_view		= strip_tags( $instance['post_view'] );
			if(isset($instance['customPost']) ){
				$custompost		= $instance['customPost'];
			}else{
				$custompost		= array();
			}
			
			if( isset($instance['light_box']) ){
                $light_box = esc_attr( $instance['light_box'] );
            }else{
                $light_box = '';
            }
			
			$args = array('exclude_from_search' => false);
			$post_types = get_post_types($args);
			
    ?>

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title',_DEV_) ?>:
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
			
			<div class="c_post">
			<?php if(sizeof($custompost)){ 
				foreach($custompost as $c_p){ 
			?>
			<p>
                <label ><?php _e('Select post type',_DEV_) ?>: 
					<a href="javascript:void(0)" onclick="jQuery(this).parent().remove();" style="float:right"><?php _e("remove",_DEV_); ?></a>
					<select class="widefat" name="<?php echo $this->get_field_name( 'customPost'  ); ?>[]" >
						<option value=''  ><?php _e('Select item',_DEV_); ?></option>	
					<?php foreach($post_types as $key => $custom_post) {  
						if('attachment' != $key && "slideshow"!= $key){ 
					?>
						<option value='<?php echo $key; ?>' <?php if($c_p == $key ){ echo 'selected="selected"'; } ?> ><?php echo $custom_post; ?></option>	
					<?php 
						} /*EOF if*/
					} /*EOF foreach*/ ?>
					</select>
                    
                </label>
			</p>
			<?php 
				} /*EOF foreach*/
			
			}else{ ?>
				<p>
					<label ><?php _e("Select post type",_DEV_) ?>:
						<a href="javascript:void(0)" onclick="jQuery(this).parent().remove();" style="float:right"><?php _e("remove",_DEV_); ?></a>
						<select class="widefat" name="<?php echo $this->get_field_name( "customPost"  ); ?>[]" >
							<option value=''  ><?php _e("Select item",_DEV_); ?></option>
						<?php foreach($post_types as $key => $custom_post) {  
							if("attachment" != $key && "slideshow"!= $key){ 
						?>
							<option value="<?php echo $key; ?>"  ><?php echo $custom_post; ?></option>	
						<?php 
							} /*EOF if*/
						} /*EOF foreach*/ ?>
						</select>
						
					</label>
				</p>
			
			<?php } /*EOF if*/ ?>
			</div>
			<p><a href="javascript:void(0)" onclick="add_more(jQuery(this));" ><?php _e('Add more',_DEV_); ?></a></p>
			<p>
                <label for="<?php echo $this->get_field_id('nr_posts'); ?>"><?php _e( 'Number of posts' , _DEV_ ) ?>:
                    <input class="widefat digit" id="<?php echo $this->get_field_id('nr_posts'); ?>" name="<?php echo $this->get_field_name('nr_posts'); ?>" type="text" value="<?php echo esc_attr( $nr_posts ); ?>" />
				</label>
            </p>
			
			<p>
                <label for="<?php echo $this->get_field_id('post_view'); ?>"><?php _e( 'Posts view' , _DEV_ ) ?>:
                    List <input type="radio" <?php if($post_view == 'list') echo 'checked="checked"'; ?> onclick="sh_lightbox(jQuery(this))" value="list" name="<?php echo $this->get_field_name('post_view'); ?>" />
					Grid <input type="radio" <?php if($post_view == 'grid') echo 'checked="checked"'; ?> onclick="sh_lightbox(jQuery(this))" value="grid" name="<?php echo $this->get_field_name('post_view'); ?>" />
				</label>
			</p>
			
			<p class="light_box <?php if($post_view == 'list'){echo 'hidden';} ?>">
				<label for="<?php echo $this->get_field_id( 'light_box' ); ?>"><?php _e( 'Open posts in lightbox window' , _DEV_ ); ?>:</label>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'light_box' ); ?>"  <?php checked( $light_box , true ); ?>  name="<?php echo $this->get_field_name( 'light_box' ); ?>"  value="1" />
			</p>
			
			<script type="text/javascript">
				function sh_lightbox(obj){
					if(obj.val() == 'list'){
						obj.parent().parent().parent().find('p.light_box').hide();
					}
					
					if(obj.val() == 'grid'){
						obj.parent().parent().parent().find('p.light_box').show();
					}
				}
				
				function add_more(obj){
					var select = '<p>';
					select += '		<label ><?php _e("Select post type",_DEV_) ?>: ';
					select += '			<a href="javascript:void(0)" onclick="jQuery(this).parent().remove();" style="float:right"><?php _e("remove",_DEV_); ?></a>';
					select += '			<select class="widefat" name="<?php echo $this->get_field_name( "customPost"  ); ?>[]" > ';
					select += '				<option value=""  ><?php _e("Select item",_DEV_); ?></option> ';
										<?php foreach($post_types as $key => $custom_post) {
												if("attachment" != $key && "slideshow"!= $key){  
										?> 
					select += '				<option value="<?php echo $key; ?>"  ><?php echo $custom_post; ?></option>	';
										<?php  
												} /*EOF if*/ 
											} /*EOF foreach*/ ?> 
					select += '			</select>';
					select += '		</label>';
					select += '	</p>';
					//alert(obj.parent().parent().find('div.c_post').attr('class'));
					jQuery(obj.parent().parent().find('div.c_post')).append(select);
				}
			</script>
    <?php
        }
		
		function list_posts(){
?>
			<script src="<?php echo get_template_directory_uri(); ?>/js/cart.js" type="text/javascript"></script>
<?php				
			// '&action=list_posts&custom_posts='+custom_posts+'&nr_posts='+nr_posts+'&post_view='+post_view+'&light_box='+light_box,
			// '&action=list_posts&active_post_type='+active_post_type+'&custom_posts='+custom_posts+'&nr_posts='+nr_posts+'&post_view='+post_view+'&light_box='+light_box+'&container_id='+container_id,
				$args = array(
					'numberposts'     => $_POST['nr_posts'],
					'post_type'       => $_POST['active_post_type'],
				);
				$cust_posts = get_posts( $args );
				
				$custom_posts = explode(',',$_POST['custom_posts']);
				
				//echo $_POST['active_post_type'];
				//echo '<ul>';
				//$cps = "<ul>";
				$cps = '';
				foreach($custom_posts as $c_p){
					if($c_p == $_POST['active_post_type']){
						$class = 'current';
						$onclick = "javascript:void(0);";
					}else{
						$class = '';
						$onclick="get_c_post(\'".$_POST['custom_posts']."\',\'".$c_p."\',". $_POST['nr_posts'].",\'". $_POST['post_view']."\',". $_POST['light_box'].",\'". $_POST['container_id']."\');";
					}
					
					$cps .= '<a class="'.$class.'" onclick="'.$onclick.'"> '.$c_p.' </a>';
				}	
				//$cps .= "</ul>";
			
?>
				<script type="text/javascript"> 
					jQuery('<?php echo '#cp_' . $_POST['container_id']; ?>').html('<?php echo $cps; ?>');
				</script>
<?php				
			//echo '<div class="'.$_POST['post_view'].'">';
			
				$counter = 1;
				$current_row = 1;
				$nr_posts = sizeof($cust_posts);
				
				if(_core::method( '_layout' , 'length' , 0 , 'front_page'  ) == 640 ){
					$posts_in_row = 2; /*for grid we'll have 2 posts in a row*/
				}else{ /*if full width*/
					$posts_in_row = 3; /*for grid we'll have 3 posts in a row*/
				}
				$nr_rows = ceil($nr_posts/$posts_in_row);
				foreach($cust_posts as $cp){ //var_dump($cp);
					if( !_core::method( '_meta' , 'logic' , $cp -> ID , 'posts-settings' , 'archive' ) ){
					$resources = _core::method( '_resources' , 'get' );
					$customID = _core::method( '_attachment' , 'getCustomIDByPostID' , $cp -> ID );
					$use_likes = false; 
						
					if(_core::method("_settings","logic","settings","blogging","likes","use"))
						{
							if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'likes-use' ] )&& $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'likes-use' ] == 'yes' ){
								$use_likes = true;
							}else if( _core::method( '_meta' , 'logic' , $cp->ID , 'posts-settings' , 'likes' ) ){
								$use_likes = true;
							}
						}

					$use_payment = false;
					$price=_core::method( '_meta' , 'get' , $cp -> ID , 'register' , 'value' );
					if( _core::method( '_meta' , 'logic' , $cp -> ID , 'register' , 'enable' ) && is_numeric($price)){
						$use_payment = _core::method( '_meta' , 'logic' , $cp -> ID , 'register' , 'use' );
						$price = _core::method( '_meta' , 'get' , $cp -> ID , 'register' , 'value' );
						$currency=_core::method('_settings','get', 'settings' , 'payment' , 'paypal' , 'currency');
					}

					if($counter > 1 && $_POST['post_view'] == 'list'){
						echo '<p class="delimiter">&nbsp;</p>';
						
					}
					if($counter == 1 && $_POST['post_view'] == 'list'){ 
						echo '<div class="element last">';
					}	
					
					if($_POST['post_view'] == 'grid'){ 
						if($counter%$posts_in_row == 1){
							$last_row_class = '';
							if($current_row == $nr_rows ){
								$last_row_class = 'last';
							}
							echo '<div class="element '.$last_row_class.'">';	
						}
						
					}
			?>
					<article <?php post_class('post col ' , $cp->ID); ?> >
						<?php 
                                                        $openFly = false;
							if($_POST['light_box'] == 1 && $_POST['post_view'] == 'grid' && get_post_format( $cp -> ID ) != 'video'){
								$post_class = 'full-screen';
								$details = '&nbsp;';
								//$onclick = 'ww()';
								$openFly = true;
							}elseif($_POST['post_view'] == 'grid'){
                                                                $post_class = 'readmore';
                                                                $details = '';
							}elseif($_POST['post_view'] == 'list'){
								$post_class = 'readmore';
								$details = 'Read more';
							}
							if(_image::thumbnail_url( $cp->ID , 'list' ) == ''){
								$details = '';
							}
						?>
						<?php if($_POST['post_view'] == 'list'){
                                                        $onclick = '';
							if( get_post_format( $cp -> ID ) == 'video' ){ 
								$format = _core::method('_meta','get',$cp -> ID , 'format');
								if( isset( $format['feat_id'] ) && !empty( $format['feat_id'] ) ){
								
									$video_id = $format['feat_id'];
									$video_type = 'self_hosted';
									if(isset($format['feat_url']) && post::isValidURL($format['feat_url']))
									  {
										$vimeo_id = post::get_vimeo_video_id( $format['feat_url'] );
										$youtube_id = post::get_youtube_video_id( $format['feat_url'] );
										
										if( $vimeo_id != '0' ){
										  $video_type = 'vimeo';
										  $video_id = $vimeo_id;
										}

										if( $youtube_id != '0' ){
										  $video_type = 'youtube';
										  $video_id = $youtube_id;
										}
									  }

									if(isset($video_type) && isset($video_id) ){
										$post_class = $post_class . ' play_video';
										if(_core::method( '_layout' , 'length' , 0 , 'front_page' ) == '930'){
											$width = 430;
											$height = 215;
										}else{
											$width = 610;
											$height = 443;
										}
										if($video_type == 'self_hosted'){
											$onclick = 'playVideo("'.urlencode(wp_get_attachment_url($video_id)).'","'.$video_type.'",jQuery(this),'.$width.','.$height.')';
										}else{
											$onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this),'.$width.','.$height.')';
										}    
										
									}
								}
							}
							
							$header_class = 'entry-header ';
							if(_core::method( '_layout' , 'length' , 0 , 'front_page'  ) == 930 ){
								$header_class .= ' b w_450'; /*set this depending on front page width !!!!*/
							}	
							
							if(_core::method( '_layout' , 'length' , 0 , 'front_page'  ) == 640 ){
								$header_class .= ' b w_610'; /*set this depending on front page width !!!!*/
							}
						?>
						<header class="<?php echo $header_class; ?>"> <!-- change w_450 acording to front page width settings -->
						<?php } ?>	
						<div class="<?php echo $post_class; ?>" <?php if(isset($onclick) && $onclick != ''){ echo "onclick=".$onclick; }?>>
							
							<a data-id="<?php echo $cp->ID ?>" href="<?php if(!isset($onclick) || $_POST['post_view'] == 'grid' ){ echo get_permalink( $cp -> ID ); }else{ echo 'javascript:void(0)'; } ?>"  class="mosaic-overlay <?php if(isset($openFly) && $openFly == true){echo 'openFly'; } ?>"> 
								<?php if( !( $_POST['post_view'] == 'grid' && $_POST['light_box'] != 1 ) && get_post_format( $cp -> ID ) != 'video' ){ ?>
									<div class="details"><?php echo $details; ?></div>
								<?php } ?>
							</a>
							<?php  
							
								
								
								/* if we can retrieve the thumb URL */
								 
								//if(_image::thumbnail_url( $cp->ID , 'list' ) != ''){
								if(_core::method( '_image' , 'thumbnail_url' , $cp->ID , 'list'  ) != ''){
									$f_img = _core::method( '_image' , 'thumbnail_url' , $cp->ID , 'list', false  );
									$img_width = '';
									$img_height = '';
									/*if the attachment width & height are bigger or equal than the size we need */
									if($_POST['post_view'] == 'grid'){
										if($f_img[1] >= _image::$img_size['grid'][0] && $f_img[2] >= _image::$img_size['grid'][1]){
											$img_width = _image::$img_size['grid'][0];
											$img_height = _image::$img_size['grid'][1];
										}
									}elseif($_POST['post_view'] == 'list'){
										if(_core::method( '_layout' , 'length' , 0 , 'front_page' ) == 930 ){
											/* for full width*/
											if($f_img[1] >= _image::$img_size['list_small'][0] && $f_img[2] >= _image::$img_size['list_small'][1]){
												$img_width = _image::$img_size['list_small'][0];
												$img_height = _image::$img_size['list_small'][1];
											}
										}
										
										if(_core::method( '_layout' , 'length' , 0 , 'front_page'  ) == 640 ){
											/* for front page w/ sidebar */
											if($f_img[1] >= _image::$img_size['list'][0] && $f_img[2] >= _image::$img_size['list'][1]){
												$img_width = _image::$img_size['list'][0];
												$img_height = _image::$img_size['list'][1];
											}
										}
									}	
									
									$img_src = _core::method( '_image' , 'thumbnail_url' , $cp->ID , 'list'  );
									$feat_img = '<img src="'.$img_src.'" alt="" width="'.$img_width.'" height="'.$img_height.'" />';
								}else{
									/*you can add here link to no_img*/
									$feat_img = '';
								}
								
								echo $feat_img;
							?>
							<?php if(_core::method( '_image' , 'thumbnail_url' , $cp->ID , 'list'  ) != ''){ ?>
									<div class="stripes">&nbsp;</div><!--Ads stripes bg-->
									<?php if($_POST['post_view'] != 'grid'){?>
											<div class="format">&nbsp;</div><!--Ads corner-->
									<?php }else{ ?>
											<div class="corner">&nbsp;</div>
									<?php } ?>
									<?php if( get_post_format( $cp->ID ) == 'video' ){?>
										<div class="play">&nbsp;</div>
									<?php } ?>
							<?php } ?>
						</div>
						<?php if($_POST['post_view'] == 'list'){ ?>
						</header>
						<?php } ?>
						<?php
							$footer_class = 'entry-footer ';
							if($_POST['post_view'] == 'list'){
								if(_core::method( '_layout' , 'length' , 0 , 'front_page'  ) == 930 ){
									$footer_class .= ' b w_450'; /*set this depending on front page width !!!!*/
								}	
								
								if(_core::method( '_layout' , 'length' , 0 , 'front_page' ) == 640 ){
									$footer_class .= ' b w_610'; /*set this depending on front page width !!!!*/
								}
							}
						?>
						<footer class="<?php echo $footer_class; ?>">
							<?php 
								if($_POST['post_view'] == 'grid'){
									echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'front_page_widgets' , 'posts_title_grid' , 'link_post_title' , $cp , 'h2'  ); 
								}else{
									echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'front_page_widgets' , 'posts_title_list' , 'link_post_title' , $cp , 'h2'  ); 
								}

							?>
							<?php if($_POST['post_view'] == 'grid'){ ?>
							<div class="excerpt"> 
								
								<?php echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'front_page_widgets' , 'simple_text' , 'text' , de_excerpt($cp->post_excerpt , $cp->post_content , $length = 150) , 'p'  );?>
							</div>
							<?php } ?>
							<div class="entry-meta">
								<?php if($_POST['post_view'] == 'grid'){ ?>
									<ul>
										<?php if( $use_payment ){
											?>
												<li class="basket">
													
													<?php echo _core::method( '_cart' , 'get_btn' , $cp->ID ); ?>
												</li>
											<?php
										}else{?>
											<li class="time">
												<a href="<?php echo get_permalink($cp->ID); ?>">
													<time>
														<?php 	if ( _core::method( '_settings' , 'logic' , 'settings' , 'general' , 'theme' , 'time' ) ) {
																	echo human_time_diff( strtotime($cp->post_date) , current_time( 'timestamp' ) ) . ' ' . __( 'ago' , _DEV_ );
																} else {
																	echo date_i18n( get_option( 'date_format' ) , strtotime($cp->post_date) );
																}?>
													</time>
												</a>
											</li>
										<?php } ?>
									</ul>
									<ul class="fr">
										<?php if($use_payment) { ?>
											 <li class="basket"><?php echo ( ( $currency == "USD" ) ? "$" : $currency ) . " " . $price ?></li>
										<?php }elseif(!$use_likes) { ?>
											<li class="cosmo-comments"><a href="<?php echo get_permalink($cp->ID) ?>"><?php echo $cp->comment_count; ?></a></li>
										<?php }else{
												echo _core::method( '_likes' , 'contentLike' , $cp -> ID );
												echo _core::method( '_likes' , 'contentHate' , $cp -> ID );
											} ?>
									</ul>
								<?php }elseif($_POST['post_view'] == 'list'){ ?>
									<ul>
										<li class="time">
											<a href="<?php echo get_permalink($cp->ID); ?>">
												<time>
													<?php 	if ( _core::method( '_settings' , 'logic' , 'settings' , 'general' , 'theme' , 'time' ) ) {
																echo human_time_diff( strtotime($cp->post_date) , current_time( 'timestamp' ) ) . ' ' . __( 'ago' , _DEV_ );
															} else {
																echo date_i18n( get_option( 'date_format' ) , strtotime($cp->post_date) );
															}?>
												</time>
											</a>
										</li>
										<li class="author"><a href="<?php echo get_author_posts_url( $cp->post_author ); ?>"><?php $user_info = get_userdata($cp->post_author); echo $user_info->user_nicename; ?></a></li>
										<?php if(!$use_likes) { ?>
											<li class="cosmo-comments"><a href="<?php echo get_permalink($cp->ID) ?>"><?php echo $cp->comment_count; ?></a></li>
										<?php }else{
												echo _core::method( '_likes' , 'contentLike' , $cp -> ID );
												echo _core::method( '_likes' , 'contentHate' , $cp -> ID );
											} ?>
									</ul>
								<?php } ?>
							</div>
							<?php if($_POST['post_view'] == 'list'){ ?>
							<div class="excerpt"> 
								<p><?php echo de_excerpt($cp->post_excerpt , $cp->post_content , $length = 150); ?></p> 
							</div>
							<div class="share">
								<?php if( $use_payment ){?>
									<?php echo _core::method( '_cart' , 'get_btn' , $cp -> ID ); ?>
									<p class="basket fr"><?php echo ( ( $currency == "USD" ) ? "$" : $currency ) . " " . $price ?></li>
								<?php }else{ ?>
									<p class="button">
										<a href="<?php echo get_permalink($cp->ID); ?>"><?php _e('continue reading',_DEV_); ?></a>
									</p>
								<?php } ?>
							</div>
							<?php } ?>
						</footer>
					</article>
					
			<?php	
					if($counter == $_POST['nr_posts'] && $_POST['post_view'] == 'list'){ 
						echo '</div>';
					}
					if($_POST['post_view'] == 'grid'){ 
						
						if($counter%$posts_in_row == 0){
							$current_row ++;
							echo '</div>'; /*close div class=element*/
						}
						
						if( $counter == $nr_posts && $counter%$posts_in_row != 0){
							echo '</div>'; /*close div class=element*/
						}
					}
					$counter ++;
					}
				}
			
			
			//echo '</div>';
			exit;
		}
		
		function get_single_posts(){
			if(isset($_POST['post_id'])){
                            $wp_query = new WP_Query( array( 'p' =>  $_POST['post_id'],
                                                            'post_type' => 'any') );

                            if( count( $wp_query -> posts ) > 0 ){ 
                                foreach( $wp_query -> posts as $post ){
                                    $wp_query -> the_post();
                                    include 'post_content.php';
                                }
                            }
			}
			
			exit;
		}
    }
	

?>