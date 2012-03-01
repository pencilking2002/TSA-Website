<?php
    class widget_sponsors extends WP_Widget {
        function widget_sponsors() {
            $widget_ops = array( 'classname' => 'sponsors_widget', 'description' => __( 'Display sponsors' , _DEV_ ) . ' <span class="cosmo-widget-red">( ' . __( 'recommend to use "Mainpage bottom side" sidebar' , _DEV_ ) . ' )</span>' );
            parent::WP_Widget( false , _CT_ . ': ' . __( 'Sponsors' , _DEV_ )  , $widget_ops );

        }

        function form($instance) {
            /*if( isset($instance['title']) ){
                $title = esc_attr($instance['title']);
            }else{
                $title = null;
            }*/

            if( isset($instance['number']) ){
                $number = esc_attr($instance['number']);
            }else{
                $number = 5;
            }

            if(isset($instance['custompost']) ){
				$custompost		= $instance['custompost'];
			}else{
				$custompost		= '';
			}
            
        	$args = array('exclude_from_search' => false);
			$post_types = get_post_types($args); 
        ?>
        
        <p>
			<label ><?php _e('Select custompost type',_DEV_) ?>: 
				
				<select class="widefat"  id="<?php echo $this->get_field_id( 'custompost' ); ?>" name="<?php echo $this->get_field_name( 'custompost' ); ?>" >
					<?php 
						foreach($post_types as $key => $custom_post) {  
							if('attachment' != $key && "slideshow"!= $key){ 
					?>
								<option value='<?php echo $key; ?>' <?php if($custompost == $key ){ echo 'selected="selected"'; } ?> ><?php echo $custom_post; ?></option>	
					<?php	
							}
						}
					?>
				</select>
				
			</label>
		</p>
		
        <p>
          <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of sponsors to show' , _DEV_ ); ?>:</label>
          <input id="<?php echo $this->get_field_id( 'number' ); ?>"  size="3" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
		
        <?php
        }

        function update( $new_instance, $old_instance) {
            $instance = $old_instance;
            //$instance['title']      = strip_tags($new_instance['title']);
            $instance['number']     = strip_tags($new_instance['number']);
            $instance['custompost']   = strip_tags($new_instance['custompost']);
            
            return $instance;
        }

        function widget($args, $instance) {
            extract( $args );
            /*if( !empty( $instance['title'] ) ){
               $title = trim( apply_filters('widget_title', $instance['title'] ) );
            }else{
               
			   $title = '';
            }*/

            if( isset($instance['number'])){
                $number = $instance['number'];
            }else{
                $number = 5;
            }


            if( isset($instance['custompost'])){
                $custompost = $instance['custompost'];
            }else{
                $custompost = '';
            }

        	          
            echo $before_widget;
  

            /*if ( !empty( $title ) ) {
                    echo $before_title . $title . $after_title;
            }*/
            
            if($custompost == ''){
				_e('Please select the cutom post type',_DEV_);
			}else{
				$args = array(
					'numberposts'     => $number,
					'post_type'       => $custompost,
				);	
				$sponsors = get_posts( $args );	 //var_dump($sponsors);
				if(sizeof($sponsors)){
?>
				<div class="w_930">
					<div class="cosmo-sponsors" id="nav-shadow">
<?php
						foreach($sponsors as $post){
							if( has_post_thumbnail( $post -> ID  ) ){
								$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , 'sponsor' , true );
?>
									<p><a href="javascript:void(0)"><img src="<?php echo $src[0]; ?>" height="40" alt="#" /></a></p>		
<?php	
								}
						
						}
?>					
						<!--<p><a href="#extern"><img src="images/sponsors/smashing.png" alt="#" /></a></p>
						<p><a href="#extern"><img src="images/sponsors/android.png" alt="#" /></a></p>
						<p><a href="#extern"><img src="images/sponsors/dribbble.png" alt="#" /></a></p>
						<p><a href="#extern"><img src="images/sponsors/google.png" alt="#" /></a></p>
						<p><a href="#extern"><img src="images/sponsors/washingtonp.png" alt="#" /></a></p>-->
					</div>
				</div>
<?php			
				}else{
					_e('No results',_DEV_);
				}
			}
			
            echo $after_widget;
        }
    }

  

?>