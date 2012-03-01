<?php
    class widget_tabber extends WP_Widget {

        function widget_tabber() {
            $widget_ops = array( 'classname' => 'widget_tabber ' , 'description' => __( 'Tabber' , _DEV_ ) );
            $this -> WP_Widget( 'widget_tabber' , _CT_ . ': ' . __( 'Tabber' , _DEV_ ) , $widget_ops );
        }

        function widget( $args , $instance ) {

            /* prints the widget*/
            extract($args, EXTR_SKIP);

            if( isset( $instance['title'] ) ){
                $title = $instance['title'];
            }else{
                $title = '';
            }

            if( isset( $instance['nr_hot_posts'] ) ){
                $nr_hot_posts = $instance['nr_hot_posts'];
            }else{
                $nr_hot_posts = 0;
            }

            if( isset( $instance['period'] ) ){
                $period = $instance['period'];
            }else{
                $period = 0;
            }

            if( isset( $instance['nr_new_posts'] ) ){
                $nr_new_posts = $instance['nr_new_posts'];
            }else{
                $nr_new_posts = 0;
            }

			if( isset( $instance['nr_comments'] ) ){
                $nr_comments = $instance['nr_comments'];
            }else{
                $nr_comments = 0;
            }

			if( isset( $instance['nr_tags'] ) ){
                $nr_tags = $instance['nr_tags'];
            }else{
                $nr_tags = 0;
            }
			
            echo $before_widget;

            if( !empty( $title ) ){
                echo $before_title . $title . $after_title;
            }

        ?>
            <!-- menu tabber -->
            <ul class="widget_tabber">
                <?php
                    if(_core::method("_settings","logic","settings","blogging","likes","use")){
                ?>
                        <li class="active"><a href="#tabber_hot_posts"><?php _e( 'feat' , _DEV_ )?></a></li>
                        <li><a href="#tabber_new_posts"><?php _e( 'fresh' , _DEV_ )?></a></li>
                <?php
                    }else{
                        ?><li class="active"><a href="#tabber_new_posts"><?php _e( 'fresh' , _DEV_ )?></a></li><?php
                    }

                    if( ! _core::method( "_settings" , "logic" , "settings" , "general" , "general_settings" , "fb_comments" ) ){
                ?>
                        <li><a href="#tabber_comments"><?php _e( 'comments' , _DEV_ )?></a></li>
                <?php
                    }
                ?>
                <li class="last"><a href="#tabber_tags_posts"><?php _e( 'tags' , _DEV_ )?></a></li>
            </ul>
			<p class="delimiter">&nbsp;</p>
            <!-- panel hot posts -->
            <?php
                if(_core::method("_settings","logic","settings","blogging","likes","use")){
                    $nclasses = 'hidden';
                }else{
                    $nclasses = '';
                }

				
                if(_core::method("_settings","logic","settings","blogging","likes","use")){
            ?>

                    <div id="tabber_hot_posts_panel" class="tab_menu_content tabs-container ">
                        <?php
                            $args = array(
                                'posts_per_page' => $nr_hot_posts,
                                'post_status' => 'publish' ,
                                'meta_key' => 'hot_date' ,
								
                                'orderby' => 'meta_value_num' ,
                                'meta_query' => array(
                                        array(
                                            'key' => 'nr_like' ,
                                            //'value' => options::get_value( 'general' , 'min_likes' ) ,
											'value' => _core::method("_settings","get","settings","blogging","likes","limit"),
                                            'compare' => '>=' ,
                                            'type' => 'numeric',
                                        ) ),
                                'order' => 'DESC'
                            );


                            /* today */
                            if( $period == 0 ){
                                $today = getdate();
                                $args['day'] = $today["mday"];
                            }

                            /* filter - 7 days */
                            if( $period == 7 ){
                                add_filter( 'posts_where', array( 'widget_tabber' , 'filter_where_07' ) );
                            }

                            /* filter - 30 days */
                            if( $period == 30 ){
                                add_filter( 'posts_where', array( 'widget_tabber' , 'filter_where_30' ) );
                            }

                            $wp_query = new WP_Query( $args );

                            /* remove filter - 7 days */
                            if( $period == 7 ){
                                remove_filter( 'posts_where', array( 'widget_tabber' , 'filter_where_07' ) );
                            }

                            /* remove filter - 30 days */
                            if( $period == 30 ){
                                remove_filter( 'posts_where', array( 'widget_tabber' , 'filter_where_30' ) );
                            }

                            /* list posts */
                            if( $wp_query -> have_posts() ){
                                echo '<div class="custom-post">';
                                foreach( $wp_query -> posts as $post ){
                                    $wp_query -> the_post();
                                    self::post( $post );
                                }
                                echo '</div>';
                            }else{
                                echo '<p>' . __( 'Sorry, no hot posts found.' , _DEV_ ) . '</p>';
                            }
                        ?>
                    </div>
            <?php
                }
            ?>

            <!-- panel new posts -->
            <div id="tabber_new_posts_panel" class="tab_menu_content tabs-container <?php  echo $nclasses; ?>">
                <?php 
                    $args = array(
                        'posts_per_page' => $nr_new_posts,
                        'post_type' => 'post'
                    );

                    $query = new WP_Query( $args );

                    /* list posts */
                    if( $query -> have_posts() ){
                        echo '<div class="custom-post">';
                        foreach( $query -> posts as $post ){
                            $query -> the_post();
                            self::post( $post );
                        }
                        echo '</div>';
                    }else{
                        echo '<p>' . __( 'Sorry, no posts found.' , _DEV_ ) . '</p>';
                    }
                ?>
            </div>

            <?php
                
				if( !_core::method( "_settings" , "logic" , "settings" , "general" , "general_settings" , "fb_comments" ) ){
            ?>
                <!-- panel comments -->
                <div id="tabber_comments_panel" class="tab_menu_content tabs-container hidden ">
                    <?php
                        $args = array(
                            'number' => $nr_comments,
                            'status' => 'approve'
                        );

                        $comments = get_comments( $args );

                        if( !empty( $comments ) && is_array( $comments ) ){
                            echo '<div class="custom-post">';
                            /* list comments */
                            foreach($comments as $comment) {

                                /* get post info */
                                $post = get_post( $comment -> comment_post_ID );

                                /* get user info */
                                $user = get_users( array( 'include' => $comment -> user_id ) );
                                $user_url = '';

                                /* get user ulr */
                                if( !empty( $user ) ){
                                    $user_url = $user[0] -> user_url;
                                }

                                /* author comment */
                                if( $comment -> comment_author_url != ''){
                                    /* get author url */
                                    $author_url = '<a href="' . $comment -> comment_author_url . '">' . mb_substr( $comment -> comment_author , 0 , 7 );
                                    if( strlen( $comment -> comment_author ) > 7 ){
                                        $author_url .=  '...</a>';
                                    }else{
                                        $author_url .= '</a>';
                                    }
                                }else{
                                    /* create user url */
                                    if( $user_url != '' ){
                                        $author_url = '<a href="' . $user_url . '">' . mb_substr( $comment -> comment_author , 0 , 7 );
                                        if( strlen( $comment -> comment_author ) > 7 ){
                                            $author_url .=  '...</a>';
                                        }else{
                                            $author_url .= '</a>';
                                        }
                                    }else{
                                        $author_url = mb_substr( $comment -> comment_author , 0 , 7 );
                                        if( strlen( $comment -> comment_author ) > 7 ){
                                            $author_url .=  '...';
                                        }
                                    }
                                }
                    ?>
                                <div class="entry">
                                    <a class="entry-img" href="<?php echo get_permalink( $comment -> comment_post_ID ) . '#comment-' . $comment -> comment_ID; ?>">
                                        <?php $size = _image::$img_size['sidebar']; echo cosmo_avatar( $comment -> user_id , $size[0] , DEFAULT_AVATAR );  ?>
                                    </a>
                                    <article class="entry-item">
                                        <h5>
                                            <a href="<?php echo get_permalink( $comment -> comment_post_ID ) . '#comment-' . $comment -> comment_ID; ?>">
                                            <?php
                                                echo strip_tags( mb_substr( $comment -> comment_content , 0 , BLOCK_TITLE_LEN-5 ) );
                                                if( strlen ( strip_tags ( $comment -> comment_content ) ) > BLOCK_TITLE_LEN-5 ){
                                                    echo ' ...';
                                                }
                                            ?>
                                            </a>
                                        </h5>
                                        <div class="entry-meta">
                                            <ul>
                                                <li class="author"><?php echo $author_url; ?></li>
												<?php

													if ( _core::method( '_settings' , 'logic' , 'settings' , 'general' , 'theme' , 'time' ) ) {
														$comment_time = human_time_diff( strtotime($comment -> comment_date) , current_time('timestamp') ) . ' ' . __( 'ago' , _DEV_ );
													}else{
														$comment_time = date_i18n( get_option( 'date_format' ) , strtotime($comment -> comment_date) ); /*echo ' '.__('at',_DEV_) . ' '. get_the_time( get_option( 'time_format' ) , $post -> ID  );*/

													}
												?>
                                                <li class="time"><time><?php echo $comment_time; ?></time></li>
                                                
                                            </ul>
                                        </div>
                                    </article>
                                </div>
                    <?php
                            }
                            echo '</div>';
                        }else{
                            echo '<p>' . __( 'There are no comments' , _DEV_ ) . '</p>';
                        }
                    ?>
                </div>
            <?php
                }
            ?>
            <!-- panel tags -->
            <div id="tabber_tags_posts_panel" class="tab_menu_content tabs-container hidden ">
                <?php
					if($nr_tags != 0){
						$args = array('number' => $nr_tags, 'orderby' => 'count', 'order' => 'DESC');
						$tags = get_tags($args);
					}else{
						$tags = get_tags();
					}	  
                    if( !empty( $tags ) && is_array( $tags ) ){
                        foreach( $tags as $tag ){
                            $tag_link = get_tag_link( $tag -> term_id );
                            ?><p class="tags"><a href="<?php echo $tag_link ?>"> <?php echo $tag -> name; ?></a></p><?php
                        }
                    }else{
                        echo '<p>' . __( 'There are no tags.' , _DEV_ ) . '</p>';
                    }
                ?>
            </div>
        <?php
            echo $after_widget;
        }

        function update( $new_instance, $old_instance) {

            /*save the widget*/
            $instance = $old_instance;
            $instance['title']              = strip_tags( $new_instance['title'] );
            $instance['nr_hot_posts']       = strip_tags( $new_instance['nr_hot_posts'] );
            $instance['period']             = strip_tags( $new_instance['period'] );
            $instance['nr_new_posts']       = strip_tags( $new_instance['nr_new_posts'] );
            $instance['nr_comments']        = strip_tags( $new_instance['nr_comments'] );
			$instance['nr_tags']        	= strip_tags( $new_instance['nr_tags'] );
			
            return $instance;
        }

        function form($instance) {

            /* widget form in backend */
            $instance       = wp_parse_args( (array) $instance, array( 'title' => '' , 'nr_hot_posts' => 10 , 'period' => 7 , 'nr_new_posts' => 10 , 'nr_comments' => 10 , 'nr_tags' => '') );
            $title          = strip_tags( $instance['title'] );
            $nr_hot_posts   = strip_tags( $instance['nr_hot_posts'] );
            $period         = strip_tags( $instance['period'] );
            $nr_new_posts   = strip_tags( $instance['nr_new_posts'] );
            $nr_comments    = strip_tags( $instance['nr_comments'] );
			$nr_tags    	= strip_tags( $instance['nr_tags'] );
			
    ?>

            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title',_DEV_) ?>:
                    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('nr_hot_posts'); ?>"><?php _e( 'Number of hot posts' , _DEV_ ) ?>:
                    <input class="widefat digit" id="<?php echo $this->get_field_id('nr_hot_posts'); ?>" name="<?php echo $this->get_field_name('nr_hot_posts'); ?>" type="text" value="<?php echo esc_attr( $nr_hot_posts ); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('period'); ?>"><?php _e( 'Period of hot posts' , _DEV_ ) ?>:
                    <select class="widefat" id="<?php echo $this->get_field_id('period'); ?>" name="<?php echo $this->get_field_name('period'); ?>">
                    <?php
                        if( $period == 0 ){
                            ?><option value="0" selected="selected"><?php _e( 'Today' , _DEV_ ); ?></option><?php
                        }else{
                            ?><option value="0"><?php _e( 'Today' , _DEV_ ); ?></option><?php
                        }

                        if( $period == 7 ){
                            ?><option value="7" selected="selected"><?php _e( '7 days' , _DEV_ ); ?></option><?php
                        }else{
                            ?><option value="7"><?php _e( '7 days' , _DEV_ ); ?></option><?php
                        }

                        if( $period == 30 ){
                            ?><option value="30" selected="selected"><?php _e( '30 days' , _DEV_ ); ?></option><?php
                        }else{
                            ?><option value="30"><?php _e( '30 days' , _DEV_ ); ?></option><?php
                        }
                    ?>
                    </select>
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('nr_new_posts'); ?>"><?php _e( 'Number of new posts' , _DEV_ ) ?>:
                    <input class="widefat digit" id="<?php echo $this->get_field_id('nr_new_posts'); ?>" name="<?php echo $this->get_field_name('nr_new_posts'); ?>" type="text" value="<?php echo esc_attr( $nr_new_posts ); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('nr_comments'); ?>"><?php _e( 'Number of comments' , _DEV_ ) ?>:
                    <input class="widefat digit" id="<?php echo $this->get_field_id('nr_comments'); ?>" name="<?php echo $this->get_field_name('nr_comments'); ?>" type="text" value="<?php echo esc_attr( $nr_comments ); ?>" />
                </label>
            </p>
			<p>
                <label for="<?php echo $this->get_field_id('nr_tags'); ?>"><?php _e( 'Number of tags' , _DEV_ ) ?>:
                    <input class="widefat digit" id="<?php echo $this->get_field_id('nr_tags'); ?>" name="<?php echo $this->get_field_name('nr_tags'); ?>" type="text" value="<?php echo esc_attr( $nr_tags ); ?>" />
					<span class="hint"><?php _e('Leave blank to show all tags',_DEV_ ) ?></span>
                </label>
            </p>
			
    <?php
        }

        /* aditional functions */
        function post( $post ){

            /* featured image */
            if( get_post_thumbnail_id( $post -> ID ) ){
                //if( is_user_logged_in () ){
                    $post_img = wp_get_attachment_image( get_post_thumbnail_id( $post -> ID ) , 'sidebar' , '' );
                    $cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
                    $cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
                    $cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
                /*}else{
                    $meta = meta::get_meta( $post -> ID , 'settings' );
                    if( isset( $meta['safe'] ) ){
                        if( !meta::logic( $post , 'settings' , 'safe' ) ){
                            $post_img = wp_get_attachment_image( get_post_thumbnail_id( $post -> ID ) , 'tsmall' , '' );
                            $cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
                            $cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
							$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
                        }else{
                            $post_img = '<img src="' . get_template_directory_uri() . '/images/nsfw.' . image::tsize('tsmall') . '.png" />';
                            $cnt_a1 = ' class="simplemodal-nsfw" href="' . get_permalink($post -> ID) . '"';
                            $cnt_a2 = ' class="simplemodal-nsfw" href="' . get_permalink($post -> ID) . '#comments"';
							$cnt_a3 = ' class="simplemodal-nsfw entry-img" href="' . get_permalink($post -> ID) . '"';
                        }
                    }else{
                        $post_img = wp_get_attachment_image( get_post_thumbnail_id( $post -> ID ) , 'tsmall' , '' );
                        $cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
                        $cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
						$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
                    }
                }*/
            }else{
                $post_img = '<img src="' . get_template_directory_uri() . '/images/no.image.50x50.png" />';
                $cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
                $cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
				$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
            }

            
        ?>
            <div class="entry">
                <a <?php echo $cnt_a3; ?>><?php echo $post_img; ?></a><!-- post featured image -->
                <article class="entry-item"><!-- post title -->
					<h5>
						<a <?php echo $cnt_a1; ?>>
							<?php
								echo mb_substr( $post -> post_title , 0 , BLOCK_TITLE_LEN );
								if( strlen( $post->post_title ) > BLOCK_TITLE_LEN ) {
									echo '...';
								}
							?>
						</a>
					</h5>
					<div class="entry-meta">
						<ul>
							<li class="cosmo-comments"><!-- comments -->
								<?php
									if ( $post -> comment_status == 'open' ) {
								?>
										<a <?php echo $cnt_a2; ?>>
											<?php
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
                                if(_core::method("_settings","logic","settings","blogging","likes","use")){
                                    echo _core::method('_likes','contentLike',$post -> ID);
									echo _core::method('_likes','contentHate',$post -> ID);
                                }
                            ?>
						</ul>
					</div>
				</article>
            </div>
        <?php
        }

        function filter_where_30( $where = '' ) {
            /* posts in the last 30 days */
			
			global $wpdb;
			
            $where .= " AND  FROM_UNIXTIME(".$wpdb->prefix."postmeta.meta_value)  > '" . date('Y-m-d', strtotime('-30 days')) . "'";
            return $where;
        }

        function filter_where_07( $where = '' ) {
            /* posts in the last 7 days */
			global $wpdb;
            $where .= " AND FROM_UNIXTIME(".$wpdb->prefix."postmeta.meta_value) > '" . date('Y-m-d', strtotime('-7 days')) . "'";
            return $where;
        }
    }
?>