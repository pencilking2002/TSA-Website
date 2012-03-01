<?php
    class widget_children_posts extends WP_Widget {
        function widget_children_posts() {
            $widget_ops = array( 'classname' => 'children-posts widget_custom_posts ', 'description' => __('Display children posts' , _DEV_ ) );
            parent::WP_Widget( false , _CT_ . ': ' . __( 'Children posts' , _DEV_ )  , $widget_ops );

        }
        function load(){
            $method = isset( $_POST['method'] ) ? $_POST['method'] : exit;
            $args   = isset( $_POST['args'] ) ? $_POST['args'] : exit;
            $object = new widget_children_posts();
            if( is_array( $method ) ){
                foreach( $method as $m ){
                    if( method_exists( $object , $m ) ){
                        echo call_user_func_array( array( 'widget_children_posts' , $m ), $args );
                    }
                }
            }else{
                if( method_exists( $object , $method ) ){
                    echo call_user_func_array( array( 'widget_children_posts' , $method ), $args );
                }
            }
            exit;
        }
        function parentCustomPostsOptions( $childrenID , $parentID ){
            $resources = _core::method( '_resources' , 'get' );
            $result = '';
            if( isset( $resources[ $childrenID ][ 'parent' ] ) &&  $resources[ $childrenID ][ 'parent' ] > -1 && isset( $resources[ $resources[ $childrenID ][ 'parent' ] ] ) ){
                if( $resources[ $childrenID ][ 'parent' ] == $parentID ){
                    $c = 'selected="selected"';
                }else{
                    $c = '';
                }
                $result  = '<option value="' . $resources[ $childrenID ][ 'parent' ] . '" ' . $c . '>' . $resources[ $resources[ $childrenID ][ 'parent' ] ][ 'stitle' ] . '</option>';
                $result .= self::parentCustomPostsOptions( $resources[ $childrenID ][ 'parent' ] , $parentID );
            }
            return $result;
        }
        function parentCustomPostsSelect( $childrenID , $parentID , $fieldID , $fieldName , $fieldPostID , $fieldPostName ){
            $resources = _core::method( '_resources' , 'get' );
            
            if( isset( $resources[ $childrenID ][ 'parent' ] ) &&  $resources[ $childrenID ][ 'parent' ] > -1 && isset( $resources[ $resources[ $childrenID ][ 'parent' ] ] ) ){
            ?>
                <p>    
                    <label for="<?php echo $fieldID ?>"><?php _e( 'Parent custom post type' , _DEV_ ); ?>:</label>
                    <select class="widefat" id="<?php echo $fieldID; ?>" name="<?php echo $fieldName; ?>" onchange="javascript:widget.r( 'parentPost' , [ tools.v( this ) , 0 , '<?php echo $fieldPostID; ?>' , '<?php echo $fieldPostName; ?>' ] , '.parent-post-id' );" >
                        <option value="-1"><?php _e( 'Select parent custom post' , _DEV_ ) ?></option>
                        <?php echo self::parentCustomPostsOptions( $childrenID , $parentID ); ?>
                    </select>
                </p>
                <div class="parent-post-id">
                </div>    
            <?php
            }
        }
        
        function parentPost( $customPostID , $postID , $fieldID , $fieldName ){
            $resources = _core::method( '_resources' , 'get' );

            if( isset( $resources[ $customPostID ] ) ){
                if( $postID > 0 ){
                    echo _fields::layout( array(
                            'type' => 'sh--search' ,
                            'set' =>  $fieldName,
                            'label' =>  __( 'Select ' , _DEV_ ) . ' ' . $resources[ $customPostID ][ 'stitle' ] . ':',
                            'id' => $fieldID,
                            'value' => $postID,
                            'query' => array(
                                'post_status' => 'publish' ,
                                'post_type' => $resources[ $customPostID ][ 'slug' ]
                            ),
                            'hint' => '<small>' . __( 'Start typing the ' , _DEV_ ) . "<strong>" . $resources[ $customPostID ][ 'stitle' ] . "</strong>" . ' ' . __( 'title' , _DEV_ ) . '</small>'
                        )
                    );
                }else{
                    echo _fields::layout( array(
                            'type' => 'sh--search' ,
                            'label' =>  __( 'Select ' , _DEV_ ) . ' ' . $resources[ $customPostID ][ 'stitle' ] . ':',
                            'set' =>  $fieldName,
                            'id' => $fieldID,
                            'query' => array(
                                'post_status' => 'publish' ,
                                'post_type' => $resources[ $customPostID ][ 'slug' ]
                            ),
                            'hint' => '<small>' . __( 'Start typing the ' , _DEV_ ) . "<strong>" . $resources[ $customPostID ][ 'stitle' ] . "</strong>" . ' ' . __( 'title' , _DEV_ ) . '</small>'
                        )
                    );
                }
            }
        }
        

        function form($instance) {
            if( isset($instance['title']) ){
                $title = esc_attr( $instance['title'] );
            }else{
                $title = __( 'Children posts' , _DEV_ );
            }
            
            if( isset( $instance[ 'childrenCustomID' ] ) ){
                $childrenCustomID = $instance[ 'childrenCustomID' ];
            }else{
                $childrenCustomID = -1;
            }
            
            if( isset( $instance[ 'parentCustomID' ] ) ){
                $parentCustomID = $instance[ 'parentCustomID' ];
            }else{
                $parentCustomID = -1;
            }
            
            if( isset( $instance[ 'parentPostID' ] ) ){
                $parentPostID = $instance[ 'parentPostID' ];
            }else{
                $parentPostID = -1;
            }
            
			if( isset( $instance[ 'nr_posts' ] ) ){
                $nr_posts = $instance[ 'nr_posts' ];
            }else{
                $nr_posts = '';
            }
			
			if( isset( $instance[ 'scrollable' ] ) ){
                $scrollable = $instance[ 'scrollable' ];
            }else{
                $scrollable = '';
            }
			
            $resources = _core::method( '_resources' , 'get' );
        ?>
            <p>
                <label for="<?php echo $this -> get_field_id( 'title' ); ?>"><?php _e( 'Widget title' , _DEV_ ); ?>:</label>
                <input class="widefat" id="<?php echo $this -> get_field_id('title'); ?>" name="<?php echo $this -> get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
			<p>
                <label for="<?php echo $this->get_field_id('nr_posts'); ?>"><?php _e( 'Number of posts' , _DEV_ ) ?>:
                    <input class="widefat digit" id="<?php echo $this->get_field_id('nr_posts'); ?>" name="<?php echo $this->get_field_name('nr_posts'); ?>" type="text" value="<?php echo esc_attr( $nr_posts ); ?>" />
				</label>
            </p>
			<p>
				<label for="<?php echo $this->get_field_id( 'scrollable' ); ?>"><?php _e( 'Scrollable' , _DEV_ ); ?>:</label>
				<input type="checkbox" id="<?php echo $this->get_field_id( 'scrollable' ); ?>"  <?php checked( $scrollable , true ); ?>  name="<?php echo $this->get_field_name( 'scrollable' ); ?>"  value="1" />
			</p>
            <p>
                <label for="<?php echo $this->get_field_id( 'childrenCustomID' ); ?>"><?php _e( 'Children custom post type' , _DEV_ ); ?>:</label>
                <select class="widefat" id="<?php echo $this -> get_field_id('childrenCustomID'); ?>" name="<?php echo $this -> get_field_name('childrenCustomID'); ?>" onchange="javascript:widget.r( 'parentCustomPostsSelect' , [ tools.v( this ) , <?php echo $parentCustomID; ?> , '<?php echo $this -> get_field_id('parentCustomID'); ?>' , '<?php echo $this -> get_field_name('parentCustomID'); ?>' , '<?php echo $this -> get_field_id('parentPostID'); ?>' , '<?php echo $this -> get_field_name('parentPostID'); ?>' ] , '.parent-id' )">
                    <?php
                        ?><option value="-1"><?php __( 'Select Custom Post Type' , _DEV_ ) ?></option><?php
                        foreach( $resources as $customID => $resource ){
                            if( $customID == $childrenCustomID ){
                                $c = 'selected="selected"';
                            }else{
                                $c = '';
                            }
                            ?><option value="<?php echo $customID; ?>" <?php echo $c; ?>><?php echo $resource[ 'stitle' ]?></option><?php
                        }
                    ?>
                </select>
            </p>
            
            <div class="parent-id">
                <p>
                    <?php
                        if( $childrenCustomID > -1 ){
                            self::parentCustomPostsSelect( $childrenCustomID , $parentCustomID , $this -> get_field_id('parentCustomID') , $this -> get_field_name('parentCustomID') , $this -> get_field_id('childrenPostID') , $this -> get_field_name('childrenPostID') );
                        }
                    ?>
                </p>
                <div class="parent-post-id">
                    <p>
                        <?php
                            if( $parentPostID > -1 ){
                                self::parentPost( $parentCustomID , $parentPostID , $this -> get_field_id('parentPostID') , $this -> get_field_name('parentPostID') );
                            }
                        ?>
                    </p>
                </div>
            </div>
        <?php
        }

        function update($new_instance, $old_instance) { //echo $new_instance[ 'childrenCustomID' ]; exit();
            $instance = $old_instance;
            $instance[ 'title' ]            = strip_tags( $new_instance[ 'title' ] );
            $instance[ 'childrenCustomID' ] = strip_tags( $new_instance[ 'childrenCustomID' ] );
            $instance[ 'parentCustomID' ]   = strip_tags( $new_instance[ 'parentCustomID' ] );
            $instance[ 'parentPostID' ]     = strip_tags( $new_instance[ 'parentPostID' ] );
			$instance['nr_posts']        	= strip_tags( $new_instance['nr_posts'] );
			$instance['scrollable']  		= strip_tags( $new_instance['scrollable']);
            return $instance;
        }

        function widget($args, $instance) { //var_dump($instance);
            extract( $args );
            if( !empty( $instance['title'] ) ){
               $title = apply_filters('widget_title', $instance['title']);
            }else{
               $title = '';
            }

            $resources = _core::method( '_resources' , 'get' ); /*get custom posts resources*/
            
			/* get children IDs for the selected post */
			if( isset( $resources[ $instance['parentCustomID'] ] ) ){
				$children_posts = _core::method( '_meta', 'get' , $instance[ 'parentPostID' ] , $resources[ $instance[ 'parentCustomID' ] ][ 'slug' ] . '-' . $resources[ $instance[ 'childrenCustomID' ] ][ 'slug' ] );
			}
			
			if( isset( $instance[ 'nr_posts' ] ) && is_numeric( $instance[ 'nr_posts' ] ) ){
                $nr_posts = $instance[ 'nr_posts' ];
            }else{
                $nr_posts = 3;
            }
			
			if( isset($instance[ 'scrollable' ] ) && $instance[ 'scrollable' ] == 1){ 
				$scrollable = $instance[ 'scrollable' ]; 
			}else{
				$scrollable = 0; 
			}
		
            echo $before_widget;

			if($scrollable == 1){
				$scroll = 'scroll-pane';
			}else{
				$scroll = '';
			}
            if ( strlen( $title ) > 0 ) {
                    echo $before_title . $title . $after_title;
            }

            if( isset( $children_posts ) && is_array( $children_posts ) && sizeof( $children_posts ) ){
				?><div class="custom-post <?php echo $scroll; ?>"><?php
					$counter = 1;
					foreach($children_posts as $post_id){ 
						if( !_core::method( '_meta' , 'logic' , $post_id , 'posts-settings' , 'archive' ) ){
						if($counter > $nr_posts) { break; }
						$post = get_post( $post_id );
						if( get_post_thumbnail_id( $post_id ) ){
								
							$post_img = wp_get_attachment_image( get_post_thumbnail_id( $post -> ID ) , 'sidebar' , '' );
							$cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
							$cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
							//$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
							
						}else{
							$post_img = '<img src="' . get_template_directory_uri() . '/images/no.image.50x50.png" />';
							$cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
							$cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
							//$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
						}
						
						/* additional info */
                        $additional = _core::method( '_meta' , 'get' , $post -> ID , 'additional' );
						//$resources  = _core::method( '_resources' , 'get' );
						
						$additional_info = '';
						if(  !empty( $additional ) && is_array( $additional ) ){
							$counter = 1;
							foreach($additional as $info){ //var_dump($info);
								if($counter > 1) break;
								$additional_info = $info;
								$counter++;
							}
						}
				?>
						<div class="entry">
							<a class="entry-img " <?php echo  $cnt_a1; ?> > <?php echo $post_img; ?></a>
							<article class="entry-item"><!-- post title -->
								<h5>
									<a class="simplemodal-nsfw" <?php echo  $cnt_a1; ?> > <?php echo  $post -> post_title ; ?></a>
								</h5>
				<?php //var_dump($additional); var_dump($resources);
								if( $additional_info != '' ){
				?>
									<div class="entry-details">
										<?php echo $additional_info;  ?>
									</div>	
				<?php		
								}else{
				?>
									<div class="entry-meta">
										<ul>
											<li class="cosmo-comments">
												<?php
													if ( $post -> comment_status == 'open' ) {
												?>
														<a <?php echo $cnt_a2; ?>>
															<?php
																//if( options::logic( 'general' , 'fb_comments' ) ){
																if( _core::method( "_settings" , "logic" , "settings" , "general" , "general_settings" , "fb_comments" ) ){
																	?> <fb:comments-count href=<?php echo get_permalink( $post -> ID  ) ?>></fb:comments-count> <?php
																}else{
																	echo $post -> comment_count . ' ';
																}
															?>
														</a>
												<?php
													}else{
														?><a><?php _e( ' Off' , _DEV_ ); ?></a><?php
													}
												?>
											</li>
											<?php
												//if( options::logic( 'general' , 'enb_likes' ) ){
												if(_core::method("_settings","logic","settings","blogging","likes","use")){
													echo _core::method('_likes','contentLike',$post -> ID);
													echo _core::method('_likes','contentHate',$post -> ID);
												}
											?>
										</ul>
									</div>
				<?php	
								}
				?>				
							</article>
						</div><!--eof entry-->
				<?php	
						$counter++;
						} /*EOF if !archived*/
					}
				?></div><?php
			}else{
				echo '<div class="clear padding15-top">';
					echo __( "We didn't find any children posts" , _DEV_ );
				echo '</div>';
			}

            echo $after_widget;
        }
    }
?>