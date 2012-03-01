<?php
    $resources = _core::method( '_resources' , 'get' );
    $customID = _core::method( '_attachment' , 'getCustomIDByPostID' , $post -> ID );
    
    $social = false;
    $cart = false;
    
    if( isset( $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'social-use' ] ) && $resources[ $customID ][ 'boxes' ][ 'posts-settings' ][ 'social-use' ] == 'yes' ){
        if( _core::method( "_meta" , "logic" , $post -> ID , 'posts-settings' , 'social' ) ){
            $social = true;
        }else{
            $settings = _core::method( "_meta" , "get" , $post -> ID , 'posts-settings' );
            if( empty( $settings ) ){
                $social = true;
            }
        }
    }
    
    if( isset( $resources[ $customID ][ 'boxes' ][ 'register' ] ) ){
        
        if( is_user_logged_in() ){
            $register = _core::method( '_meta' , 'get' , $post -> ID , 'register'  );
            
            if( is_array( $register ) && isset( $register[ 'use' ] ) && $register[ 'use' ] == 'yes' && isset( $register[ 'value' ] ) && !empty( $register[ 'value' ] ) && isset( $register[ 'value' ] ) && (int)$register['quantity'] > 0 &&  isset( $register[ 'enable' ] ) && $register[ 'enable' ] == 'yes' ){
                $cart = true;
            }
        }
    }
    
    if( $social || $cart ){
        if( !_core::method( "_meta" , "logic" , $post -> ID , 'posts-settings' , 'social-position' ) ){
            $align = 'left';
        }else{
            $align = 'right';
        }
?>
        <div id="share_buttons_wrapper" class="<?php echo $align; ?>">
            <div id="share_buttons_single_page" class="share_buttons_single_page">
                <?php
                    if( $cart ){
                        get_template_part( '/templates/single/cart' );
                    }
                    
                    if( $social ){
                        get_template_part( '/templates/single/social' );
                    }
                ?>
            </div>
        </div>
<?php
    }
?>