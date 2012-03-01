<?php
    global $wp_query;        
    $wp_query = new WP_Query( $args );

    foreach( $wp_query -> posts as $p ){
        $wp_query -> the_post();

        if( true OR false /* is grid */ ){
            /* grid posts */
        }else{
            /* lists posts */
        }
    }
    
    _core::hook( 'children' );
    
    
?>