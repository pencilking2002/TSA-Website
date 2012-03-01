<?php
    if( have_posts() ){
        while( have_posts() ){
            the_post();
            
            //grid  OR
            //list
            _core::hook( 'loop' );
        }
    }
?>
